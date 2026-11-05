<?php

use Illuminate\Support\Facades\Route;

// Location API routes (public - no auth required)
Route::get('api/locations/provinces', [App\Http\Controllers\Api\LocationController::class, 'getProvinces'])->name('api.locations.provinces');
Route::get('api/locations/districts', [App\Http\Controllers\Api\LocationController::class, 'getDistricts'])->name('api.locations.districts');

// Portfolio Management route moved to auth group below
Route::get('portfolio/management', [App\Http\Controllers\PortfolioManagementController::class, 'index'])->name('portfolio.management.index');

// Portfolio Financials index (was missing, previously caught by catch-all and redirected to dashboard)
Route::get('portfolio/financials', [App\Http\Controllers\PortfolioFinancialController::class, 'index'])->name('portfolio.financials.index');

// Debug route: return markers payload for portfolio map (only in local environment)
Route::get('debug/portfolio/markers', function(){
	if(app()->environment() !== 'local' && !in_array(config('app.debug'), [true, 'true'])){
		abort(404);
	}
	$projects = App\Models\Project::all();
	$markers = $projects->map(function($p){
		$loc = $p->location ?? null; $lat = null; $lng = null;
		if(is_array($loc)) { $lat = $loc['lat'] ?? null; $lng = $loc['lng'] ?? null; }
		elseif(is_object($loc)) { $lat = $loc->lat ?? null; $lng = $loc->lng ?? null; }
		return [
			'id' => $p->id,
			'name' => $p->name,
			'code' => $p->code,
			'budget' => $p->budget ?? 0,
			'progress_percent' => $p->progress_percent ?? 0,
			'lat' => $lat, 'lng' => $lng, 'status' => $p->status ?? ''
		];
	})->filter(function($m){ return $m['lat'] !== null && $m['lng'] !== null; })->values();
	return response()->json($markers);
});

// Debug route: return chart payloads used by PortfolioManagementController (only in local/dev)
Route::get('debug/portfolio/charts', function(){
	if(app()->environment() !== 'local' && !in_array(config('app.debug'), [true, 'true'])){
		abort(404);
	}

	$months = ['Nisan', 'MayÄ±s', 'Haziran', 'Temmuz', 'AÄŸustos', 'EylÃ¼l'];

	// Health trend
	$healthTrendData = [];
	foreach ($months as $month) {
		$idx = array_search($month, $months) + 4; // Nisan = 4
		$healthy = App\Models\Project::where('health_status', 'healthy')->whereMonth('created_at', $idx)->count();
		$risky = App\Models\Project::where('health_status', 'risky')->whereMonth('created_at', $idx)->count();
		$critical = App\Models\Project::where('health_status', 'critical')->whereMonth('created_at', $idx)->count();
		$healthTrendData[] = ['month' => $month, 'healthy' => $healthy, 'risky' => $risky, 'critical' => $critical];
	}

	// Budget-time scatter (tolerant: NULL -> 0)
	$budgetTimeScatterData = App\Models\Project::where('status', '!=', 'completed')
		->select('name', 'budget', 'spent', 'progress_percent', 'end_date')
		->get()
		->map(function($project){
			$budget = $project->budget ?? 0;
			$spent = $project->spent ?? 0;
			$progress = $project->progress_percent ?? 0;
			$budgetVariance = $budget > 0 ? (($spent - $budget) / $budget) * 100 : 0;
			$scheduleVariance = 100 - $progress;
			return [
				'name' => $project->name ?? 'Unnamed',
				'budget_variance' => round($budgetVariance, 1),
				'schedule_variance' => round($scheduleVariance, 1),
			];
		})
		->filter(function($item){
			return abs($item['budget_variance']) < 200 && abs($item['schedule_variance']) < 200;
		})->values()->toArray();

	// Performance comparison
	$data = [];
	$types = ['residential', 'commercial', 'infrastructure', 'industrial'];
	foreach ($types as $type) {
		$projects = App\Models\Project::where('type', $type)->get();
		$totalProjects = $projects->count();
		if ($totalProjects > 0) {
			$onTimeRate = $projects->where('progress_percent', '>=', 80)->count() / $totalProjects * 100;
			$avgProfitMargin = $projects->avg(function($p){
				return $p->budget > 0 ? (($p->budget - $p->spent) / $p->budget) * 100 : 0;
			});
			$data[] = ['category' => ucfirst($type), 'on_time_rate' => round($onTimeRate, 1), 'profit_margin' => round($avgProfitMargin, 1)];
		}
	}
	$regions = ['Marmara', 'Ege', 'Ä°Ã§ Anadolu', 'Akdeniz', 'Karadeniz', 'DoÄŸu Anadolu', 'GÃ¼neydoÄŸu Anadolu'];
	foreach ($regions as $region) {
		$projects = App\Models\Project::where('region', $region)->get();
		$totalProjects = $projects->count();
		if ($totalProjects > 0) {
			$onTimeRate = $projects->where('progress_percent', '>=', 80)->count() / $totalProjects * 100;
			$avgProfitMargin = $projects->avg(function($p){
				return $p->budget > 0 ? (($p->budget - $p->spent) / $p->budget) * 100 : 0;
			});
			$data[] = ['category' => $region, 'on_time_rate' => round($onTimeRate, 1), 'profit_margin' => round($avgProfitMargin, 1)];
		}
	}

	$performanceComparisonData = array_slice($data, 0, 10);

	return response()->json([
		'healthTrendData' => $healthTrendData,
		'budgetTimeScatterData' => $budgetTimeScatterData,
		'performanceComparisonData' => $performanceComparisonData,
	]);
});

// Simple authentication routes (must be before catch-all)
Route::get('auth-login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login.show');
Route::post('auth-login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Explicit portfolio route(s) - listing handled by ProjectController (supports search & pagination)
Route::get('portfolio/all-projects', [App\Http\Controllers\ProjectController::class, 'index'])->name('portfolio.all-projects');
Route::get('portfolio/projects/{id}', [App\Http\Controllers\ProjectController::class, 'show'])->name('portfolio.project.show');
Route::post('portfolio/projects/{id}/like', [App\Http\Controllers\ProjectLikeController::class, 'store'])->name('portfolio.project.like');
// API-style endpoint for project preview (used by modal preview)
Route::get('api/projects/{id}', [App\Http\Controllers\ProjectController::class, 'apiShow']);

// Program yÃ¶netimi routes
Route::get('portfolio/program-management', [App\Http\Controllers\ProgramController::class, 'index'])->name('portfolio.program-management.index');
// fixed routes first to avoid being captured by parameterized routes
Route::get('portfolio/programs/export/csv', [App\Http\Controllers\ProgramController::class, 'exportCsv'])->name('portfolio.management.export');
Route::get('portfolio/programs/create', [App\Http\Controllers\ProgramController::class, 'create'])->name('portfolio.management.create');
Route::post('portfolio/programs', [App\Http\Controllers\ProgramController::class, 'store'])->name('portfolio.management.store');
Route::get('portfolio/programs/{id}/edit', [App\Http\Controllers\ProgramController::class, 'edit'])->name('portfolio.management.edit');
Route::put('portfolio/programs/{id}', [App\Http\Controllers\ProgramController::class, 'update'])->name('portfolio.management.update');
Route::delete('portfolio/programs/{id}', [App\Http\Controllers\ProgramController::class, 'destroy'])->name('portfolio.management.destroy');
// parameterized show route should come after more specific ones
Route::get('portfolio/programs/{id}', [App\Http\Controllers\ProgramController::class, 'show'])->name('portfolio.management.show');

// Milestone AJAX CRUD (protected)

	// User settings: set page lock state (AJAX)
	Route::post('user/settings/page-lock', [App\Http\Controllers\UserSettingsController::class, 'setPageLock'])->name('user.settings.page-lock');

Route::middleware('auth')->group(function(){
	// Admin - KullanÄ±cÄ± YÃ¶netimi
	Route::get('admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
	Route::get('admin/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.users.create');
	Route::post('admin/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');
	Route::get('admin/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
	Route::get('admin/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
	Route::put('admin/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
	Route::delete('admin/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
	Route::post('admin/users/{user}/toggle-admin', [App\Http\Controllers\Admin\UserController::class, 'toggleAdmin'])->name('admin.users.toggle-admin');
	Route::post('admin/users/{user}/toggle-verification', [App\Http\Controllers\Admin\UserController::class, 'toggleVerification'])->name('admin.users.toggle-verification');
	Route::post('admin/users/bulk-action', [App\Http\Controllers\Admin\UserController::class, 'bulkAction'])->name('admin.users.bulk-action');
	Route::get('admin/users/export/csv', [App\Http\Controllers\Admin\UserController::class, 'exportCsv'])->name('admin.users.export');

	// KDV oranlarÄ± yÃ¶netimi (admin)
	Route::get('admin/kdv', [App\Http\Controllers\Admin\KdvRateController::class, 'index'])->name('admin.kdv.index');
	Route::get('admin/kdv/create', [App\Http\Controllers\Admin\KdvRateController::class, 'create'])->name('admin.kdv.create');
	Route::post('admin/kdv', [App\Http\Controllers\Admin\KdvRateController::class, 'store'])->name('admin.kdv.store');
	Route::get('admin/kdv/{kdvRate}/edit', [App\Http\Controllers\Admin\KdvRateController::class, 'edit'])->name('admin.kdv.edit');
	Route::put('admin/kdv/{kdvRate}', [App\Http\Controllers\Admin\KdvRateController::class, 'update'])->name('admin.kdv.update');
	Route::delete('admin/kdv/{kdvRate}', [App\Http\Controllers\Admin\KdvRateController::class, 'destroy'])->name('admin.kdv.destroy');

	Route::post('portfolio/programs/{program}/milestones', [App\Http\Controllers\ProgramController::class, 'storeMilestone'])->name('portfolio.programs.milestones.store');
	Route::put('portfolio/programs/{program}/milestones/{milestone}', [App\Http\Controllers\ProgramController::class, 'updateMilestone'])->name('portfolio.programs.milestones.update');
	Route::delete('portfolio/programs/{program}/milestones/{milestone}', [App\Http\Controllers\ProgramController::class, 'destroyMilestone'])->name('portfolio.programs.milestones.destroy');
});

// Portfolio Financials index (was missing, previously caught by catch-all and redirected to dashboard)
Route::get('portfolio/financials', [App\Http\Controllers\PortfolioFinancialController::class, 'index'])->name('portfolio.financials.index');

// Debug route: return markers payload for portfolio map (only in local environment)
Route::get('debug/portfolio/markers', function(){
	if(app()->environment() !== 'local' && !in_array(config('app.debug'), [true, 'true'])){
		abort(404);
	}
	$projects = App\Models\Project::all();
	$markers = $projects->map(function($p){
		$loc = $p->location ?? null; $lat = null; $lng = null;
		if(is_array($loc)) { $lat = $loc['lat'] ?? null; $lng = $loc['lng'] ?? null; }
		elseif(is_object($loc)) { $lat = $loc->lat ?? null; $lng = $loc->lng ?? null; }
		return [
			'id' => $p->id,
			'name' => $p->name,
			'code' => $p->code,
			'budget' => $p->budget ?? 0,
			'progress_percent' => $p->progress_percent ?? 0,
			'lat' => $lat, 'lng' => $lng, 'status' => $p->status ?? ''
		];
	})->filter(function($m){ return $m['lat'] !== null && $m['lng'] !== null; })->values();
	return response()->json($markers);
});

// Debug route: return chart payloads used by PortfolioManagementController (only in local/dev)
Route::get('debug/portfolio/charts', function(){
	if(app()->environment() !== 'local' && !in_array(config('app.debug'), [true, 'true'])){
		abort(404);
	}

	$months = ['Nisan', 'MayÄ±s', 'Haziran', 'Temmuz', 'AÄŸustos', 'EylÃ¼l'];

	// Health trend
	$healthTrendData = [];
	foreach ($months as $month) {
		$idx = array_search($month, $months) + 4; // Nisan = 4
		$healthy = App\Models\Project::where('health_status', 'healthy')->whereMonth('created_at', $idx)->count();
		$risky = App\Models\Project::where('health_status', 'risky')->whereMonth('created_at', $idx)->count();
		$critical = App\Models\Project::where('health_status', 'critical')->whereMonth('created_at', $idx)->count();
		$healthTrendData[] = ['month' => $month, 'healthy' => $healthy, 'risky' => $risky, 'critical' => $critical];
	}

	// Budget-time scatter (tolerant: NULL -> 0)
	$budgetTimeScatterData = App\Models\Project::where('status', '!=', 'completed')
		->select('name', 'budget', 'spent', 'progress_percent', 'end_date')
		->get()
		->map(function($project){
			$budget = $project->budget ?? 0;
			$spent = $project->spent ?? 0;
			$progress = $project->progress_percent ?? 0;
			$budgetVariance = $budget > 0 ? (($spent - $budget) / $budget) * 100 : 0;
			$scheduleVariance = 100 - $progress;
			return [
				'name' => $project->name ?? 'Unnamed',
				'budget_variance' => round($budgetVariance, 1),
				'schedule_variance' => round($scheduleVariance, 1),
			];
		})
		->filter(function($item){
			return abs($item['budget_variance']) < 200 && abs($item['schedule_variance']) < 200;
		})->values()->toArray();

	// Performance comparison
	$data = [];
	$types = ['residential', 'commercial', 'infrastructure', 'industrial'];
	foreach ($types as $type) {
		$projects = App\Models\Project::where('type', $type)->get();
		$totalProjects = $projects->count();
		if ($totalProjects > 0) {
			$onTimeRate = $projects->where('progress_percent', '>=', 80)->count() / $totalProjects * 100;
			$avgProfitMargin = $projects->avg(function($p){
				return $p->budget > 0 ? (($p->budget - $p->spent) / $p->budget) * 100 : 0;
			});
			$data[] = ['category' => ucfirst($type), 'on_time_rate' => round($onTimeRate, 1), 'profit_margin' => round($avgProfitMargin, 1)];
		}
	}
	$regions = ['Marmara', 'Ege', 'Ä°Ã§ Anadolu', 'Akdeniz', 'Karadeniz', 'DoÄŸu Anadolu', 'GÃ¼neydoÄŸu Anadolu'];
	foreach ($regions as $region) {
		$projects = App\Models\Project::where('region', $region)->get();
		$totalProjects = $projects->count();
		if ($totalProjects > 0) {
			$onTimeRate = $projects->where('progress_percent', '>=', 80)->count() / $totalProjects * 100;
			$avgProfitMargin = $projects->avg(function($p){
				return $p->budget > 0 ? (($p->budget - $p->spent) / $p->budget) * 100 : 0;
			});
			$data[] = ['category' => $region, 'on_time_rate' => round($onTimeRate, 1), 'profit_margin' => round($avgProfitMargin, 1)];
		}
	}

	$performanceComparisonData = array_slice($data, 0, 10);

	return response()->json([
		'healthTrendData' => $healthTrendData,
		'budgetTimeScatterData' => $budgetTimeScatterData,
		'performanceComparisonData' => $performanceComparisonData,
	]);
});

// Simple authentication routes (must be before catch-all)
Route::get('auth-login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login.show');
Route::post('auth-login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Explicit portfolio route(s) - listing handled by ProjectController (supports search & pagination)
Route::get('portfolio/all-projects', [App\Http\Controllers\ProjectController::class, 'index'])->name('portfolio.all-projects');
Route::get('portfolio/projects/{id}', [App\Http\Controllers\ProjectController::class, 'show'])->name('portfolio.project.show');
Route::post('portfolio/projects/{id}/like', [App\Http\Controllers\ProjectLikeController::class, 'store'])->name('portfolio.project.like');
// API-style endpoint for project preview (used by modal preview)
Route::get('api/projects/{id}', [App\Http\Controllers\ProjectController::class, 'apiShow']);

// Program yÃ¶netimi routes
Route::get('portfolio/program-management', [App\Http\Controllers\ProgramController::class, 'index'])->name('portfolio.program-management.index');
// fixed routes first to avoid being captured by parameterized routes
Route::get('portfolio/programs/export/csv', [App\Http\Controllers\ProgramController::class, 'exportCsv'])->name('portfolio.management.export');
Route::get('portfolio/programs/create', [App\Http\Controllers\ProgramController::class, 'create'])->name('portfolio.management.create');
Route::post('portfolio/programs', [App\Http\Controllers\ProgramController::class, 'store'])->name('portfolio.management.store');
Route::get('portfolio/programs/{id}/edit', [App\Http\Controllers\ProgramController::class, 'edit'])->name('portfolio.management.edit');
Route::put('portfolio/programs/{id}', [App\Http\Controllers\ProgramController::class, 'update'])->name('portfolio.management.update');
Route::delete('portfolio/programs/{id}', [App\Http\Controllers\ProgramController::class, 'destroy'])->name('portfolio.management.destroy');
// parameterized show route should come after more specific ones
Route::get('portfolio/programs/{id}', [App\Http\Controllers\ProgramController::class, 'show'])->name('portfolio.management.show');

// Milestone AJAX CRUD (protected)

	// User settings: set page lock state (AJAX)
	Route::post('user/settings/page-lock', [App\Http\Controllers\UserSettingsController::class, 'setPageLock'])->name('user.settings.page-lock');

Route::middleware('auth')->group(function(){
	// Admin - KullanÄ±cÄ± YÃ¶netimi
	Route::get('admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
	Route::get('admin/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.users.create');
	Route::post('admin/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');
	Route::get('admin/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
	Route::get('admin/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
	Route::put('admin/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
	Route::delete('admin/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
	Route::post('admin/users/{user}/toggle-admin', [App\Http\Controllers\Admin\UserController::class, 'toggleAdmin'])->name('admin.users.toggle-admin');
	Route::post('admin/users/{user}/toggle-verification', [App\Http\Controllers\Admin\UserController::class, 'toggleVerification'])->name('admin.users.toggle-verification');
	Route::post('admin/users/bulk-action', [App\Http\Controllers\Admin\UserController::class, 'bulkAction'])->name('admin.users.bulk-action');
	Route::get('admin/users/export/csv', [App\Http\Controllers\Admin\UserController::class, 'exportCsv'])->name('admin.users.export');

	// KDV oranlarÄ± yÃ¶netimi (admin)
	Route::get('admin/kdv', [App\Http\Controllers\Admin\KdvRateController::class, 'index'])->name('admin.kdv.index');
	Route::get('admin/kdv/create', [App\Http\Controllers\Admin\KdvRateController::class, 'create'])->name('admin.kdv.create');
	Route::post('admin/kdv', [App\Http\Controllers\Admin\KdvRateController::class, 'store'])->name('admin.kdv.store');
	Route::get('admin/kdv/{kdvRate}/edit', [App\Http\Controllers\Admin\KdvRateController::class, 'edit'])->name('admin.kdv.edit');
	Route::put('admin/kdv/{kdvRate}', [App\Http\Controllers\Admin\KdvRateController::class, 'update'])->name('admin.kdv.update');
	Route::delete('admin/kdv/{kdvRate}', [App\Http\Controllers\Admin\KdvRateController::class, 'destroy'])->name('admin.kdv.destroy');

	Route::post('portfolio/programs/{program}/milestones', [App\Http\Controllers\ProgramController::class, 'storeMilestone'])->name('portfolio.programs.milestones.store');
	Route::put('portfolio/programs/{program}/milestones/{milestone}', [App\Http\Controllers\ProgramController::class, 'updateMilestone'])->name('portfolio.programs.milestones.update');
	Route::delete('portfolio/programs/{program}/milestones/{milestone}', [App\Http\Controllers\ProgramController::class, 'destroyMilestone'])->name('portfolio.programs.milestones.destroy');
});
// Portfolio Management route moved to auth group below
Route::get('portfolio/financials/export', [App\Http\Controllers\PortfolioFinancialController::class, 'exportCsv'])->name('portfolio.financials.export');
Route::get('portfolio/financials/export-xlsx', [App\Http\Controllers\PortfolioFinancialController::class, 'exportXlsx'])->name('portfolio.financials.export_xlsx');
// BÃ¼tÃ§e ve Maliyet KontrolÃ¼ (Cost & Budget Control)
Route::get('cost/budget', [App\Http\Controllers\CostBudgetController::class, 'index'])->name('cost.budget');
// HakediÅŸler / Ã–demeler - Payments module
Route::get('cost/payments', [App\Http\Controllers\PaymentController::class, 'index'])->name('cost.payments.index');
Route::get('cost/payments/{id}', [App\Http\Controllers\PaymentController::class, 'show'])->name('cost.payments.show')->where('id', '[0-9]+');
Route::middleware('auth')->group(function(){
	// Field operations page
	Route::get('field/operations', function() { return view('field.operations'); })->name('field.operations');

		// QHSE - quality and punchlist pages (render blades directly)
		// These are simple, client-side prototype pages stored in resources/views/field/qhse
		Route::get('field/qhse/quality', function() { return view('field.qhse.quality'); })->name('field.qhse.quality');
		Route::get('field/qhse/punchlist', function() { return view('field.qhse.punchlist'); })->name('field.qhse.punchlist');

		// QHSE API (basic stubs)
		Route::get('api/qhse/inspections', [App\Http\Controllers\Api\QhseInspectionController::class, 'index']);
		Route::get('api/qhse/inspections/{id}', [App\Http\Controllers\Api\QhseInspectionController::class, 'show']);
		Route::post('api/qhse/inspections', [App\Http\Controllers\Api\QhseInspectionController::class, 'store']);
		Route::put('api/qhse/inspections/{id}', [App\Http\Controllers\Api\QhseInspectionController::class, 'update']);
		Route::post('api/qhse/punchlist', [App\Http\Controllers\Api\QhseInspectionController::class, 'createPunch']);
		Route::post('api/qhse/media', [App\Http\Controllers\Api\QhseInspectionController::class, 'uploadMedia']);
		Route::put('api/qhse/punchlist/{id}', [App\Http\Controllers\Api\QhseInspectionController::class, 'updatePunch']);
		Route::post('api/qhse/punchlist/{id}/activity', [App\Http\Controllers\Api\QhseInspectionController::class, 'postPunchActivity']);
	// Field operations API (basic)
	Route::post('api/field/reports', [App\Http\Controllers\FieldOperationsController::class, 'store'])->name('api.field.reports.store');
	Route::get('api/field/reports/{project}/{date}', [App\Http\Controllers\FieldOperationsController::class, 'show'])->name('api.field.reports.show');
	Route::post('api/field/media', [App\Http\Controllers\FieldOperationsController::class, 'uploadMedia'])->name('api.field.media.upload');
	Route::get('api/field/media', [App\Http\Controllers\FieldOperationsController::class, 'mediaIndex'])->name('api.field.media.index');
	Route::post('api/field/media/attach', [App\Http\Controllers\FieldOperationsController::class, 'attachMedia'])->name('api.field.media.attach');
	Route::get('api/field/reports/statuses', [App\Http\Controllers\FieldOperationsController::class, 'statuses'])->name('api.field.reports.statuses');
	Route::get('api/field/weather', [App\Http\Controllers\FieldOperationsController::class, 'getWeather'])->name('api.field.weather');
	Route::get('api/field/tasks', [App\Http\Controllers\FieldOperationsController::class, 'getTasks'])->name('api.field.tasks');
	Route::get('api/field/equipment', [App\Http\Controllers\FieldOperationsController::class, 'getEquipment'])->name('api.field.equipment');
	Route::get('api/field/vendors', [App\Http\Controllers\FieldOperationsController::class, 'getVendors'])->name('api.field.vendors');
	// Claims (Hak Talepleri)
	Route::get('contracts/claims', [App\Http\Controllers\ClaimsController::class, 'index'])->name('contracts.claims');
	Route::get('contracts/claims/create', [App\Http\Controllers\ClaimsController::class, 'create'])->name('contracts.claims.create');
	Route::post('contracts/claims', [App\Http\Controllers\ClaimsController::class, 'store'])->name('contracts.claims.store');
	Route::get('contracts/claims/{claim}', [App\Http\Controllers\ClaimsController::class, 'show'])->name('contracts.claims.show');
	// Contract library (templates & clauses)
	Route::get('contracts/library', [App\Http\Controllers\ContractLibraryController::class, 'index'])->name('contracts.library.index');

	// Ä°SG (Ä°ÅŸ SaÄŸlÄ±ÄŸÄ± ve GÃ¼venliÄŸi) Routes
	Route::prefix('field/qhse/safety')->name('field.qhse.safety.')->group(function() {
		Route::get('/', [App\Http\Controllers\IsgController::class, 'dashboard'])->name('dashboard');

		// Olay ve Kaza Raporlama
		Route::get('incidents', [App\Http\Controllers\IsgController::class, 'incidentIndex'])->name('incidents.index');
		Route::get('incidents/create', [App\Http\Controllers\IsgController::class, 'incidentCreate'])->name('incident.create');
		Route::post('incidents', [App\Http\Controllers\IsgController::class, 'incidentStore'])->name('incident.store');
		Route::get('incidents/{incident}', [App\Http\Controllers\IsgController::class, 'incidentShow'])->name('incident.show');

		// Ä°ÅŸe BaÅŸlama Ä°zinleri (Permit to Work)
		Route::get('permits', [App\Http\Controllers\IsgController::class, 'permitsIndex'])->name('permits.index');
		Route::get('permits/create', [App\Http\Controllers\IsgController::class, 'permitCreate'])->name('permit.create');
		Route::post('permits', [App\Http\Controllers\IsgController::class, 'permitStore'])->name('permit.store');
		Route::get('permits/{permit}', [App\Http\Controllers\IsgController::class, 'permitShow'])->name('permit.show');
		Route::post('permits/{permit}/approve', [App\Http\Controllers\IsgController::class, 'permitApprove'])->name('permit.approve');
		Route::post('permits/{permit}/close', [App\Http\Controllers\IsgController::class, 'permitClose'])->name('permit.close');

		// Saha GÃ¼venlik EÄŸitimleri (Toolbox Talks)
		Route::get('toolbox', [App\Http\Controllers\IsgController::class, 'toolboxIndex'])->name('toolbox.index');
		Route::get('toolbox/create', [App\Http\Controllers\IsgController::class, 'toolboxCreate'])->name('toolbox.create');
		Route::post('toolbox', [App\Http\Controllers\IsgController::class, 'toolboxStore'])->name('toolbox.store');
	});
	Route::get('contracts/library/templates/create', [App\Http\Controllers\ContractLibraryController::class, 'createTemplate'])->name('contracts.library.templates.create');
	Route::post('contracts/library/templates', [App\Http\Controllers\ContractLibraryController::class, 'storeTemplate'])->name('contracts.library.templates.store');
	Route::get('contracts/library/clauses/create', [App\Http\Controllers\ContractLibraryController::class, 'createClause'])->name('contracts.library.clauses.create');
	Route::post('contracts/library/clauses', [App\Http\Controllers\ContractLibraryController::class, 'storeClause'])->name('contracts.library.clauses.store');
	Route::get('cost/payments/create', [App\Http\Controllers\PaymentController::class, 'create'])->name('cost.payments.create');
	Route::post('cost/payments', [App\Http\Controllers\PaymentController::class, 'store'])->name('cost.payments.store');
	Route::post('cost/payments/items/{item}/evidence', [App\Http\Controllers\PaymentController::class, 'uploadEvidence'])->name('cost.payments.upload_evidence');
	Route::get('cost/payments/export/csv', [App\Http\Controllers\PaymentController::class, 'exportCsv'])->name('cost.payments.export');
	Route::post('cost/payments/{payment}/approve', [App\Http\Controllers\PaymentController::class, 'approve'])->name('cost.payments.approve');
});

// ERP Entegrasyonu
Route::middleware('auth')->group(function(){
	Route::get('cost/erp', [App\Http\Controllers\ErpController::class, 'index'])->name('cost.erp.index');
	Route::post('cost/erp/sync', [App\Http\Controllers\ErpController::class, 'sync'])->name('cost.erp.sync');
	Route::post('cost/erp/mappings', [App\Http\Controllers\ErpController::class, 'saveMapping'])->name('cost.erp.mappings.save');
	Route::post('cost/erp/settings', [App\Http\Controllers\ErpController::class, 'saveSettings'])->name('cost.erp.settings.save');
});
// Ä°nsan KaynaklarÄ± (HR) Routes
Route::middleware('auth')->group(function(){
	Route::prefix('resources/hr')->name('resources.hr.')->group(function() {
		Route::get('/', [App\Http\Controllers\HRController::class, 'dashboard'])->name('dashboard');
		Route::get('workforce-planning', [App\Http\Controllers\HRController::class, 'workforcePlanning'])->name('workforce-planning');
		Route::get('employees', [App\Http\Controllers\HRController::class, 'employeeList'])->name('employees');
		Route::get('employees/{id}', [App\Http\Controllers\HRController::class, 'employeeProfile'])->name('employee.profile');
		Route::get('certificates', [App\Http\Controllers\HRController::class, 'certificates'])->name('certificates');

		// API routes for workforce planning
		Route::get('api/assignments', [App\Http\Controllers\HRController::class, 'apiGetAssignments'])->name('api.assignments');
		Route::post('api/assign-employee', [App\Http\Controllers\HRController::class, 'apiAssignEmployee'])->name('api.assign-employee');
	});
});
// Portfolio Management Dashboard
Route::get('portfolio/management', [App\Http\Controllers\PortfolioManagementController::class, 'index'])->name('portfolio.management.index');
// Opportunities (Potansiyel Projeler / CRM)
Route::get('opportunities', [App\Http\Controllers\OpportunityController::class, 'index'])->name('opportunities.index');
Route::get('opportunities/create', [App\Http\Controllers\OpportunityController::class, 'create'])->name('opportunities.create');
Route::post('opportunities', [App\Http\Controllers\OpportunityController::class, 'store'])->name('opportunities.store');
Route::get('opportunities/{id}', [App\Http\Controllers\OpportunityController::class, 'show'])->name('opportunities.show');
Route::post('opportunities/{id}/move', [App\Http\Controllers\OpportunityController::class, 'moveStage'])->name('opportunities.move');
Route::post('opportunities/{id}/convert', [App\Http\Controllers\OpportunityController::class, 'convertToProject'])->name('opportunities.convert');
Route::post('opportunities/{id}/activity', [App\Http\Controllers\OpportunityController::class, 'addActivity'])->name('opportunities.activity');
Route::post('opportunities/{id}/note', [App\Http\Controllers\OpportunityController::class, 'addNote'])->name('opportunities.note');
Route::delete('opportunities/{id}', [App\Http\Controllers\OpportunityController::class, 'destroy'])->name('opportunities.destroy');
Route::post('opportunities/bulk-action', [App\Http\Controllers\OpportunityController::class, 'bulkAction'])->name('opportunities.bulk');
Route::get('opportunities/export/csv', [App\Http\Controllers\OpportunityController::class, 'exportCsv'])->name('opportunities.export');
// Feasibility & Capital Planning
Route::get('portfolio/tenders/feasibility-capital', [App\Http\Controllers\FeasibilityController::class, 'index'])->name('feasibility.index');
Route::get('portfolio/tenders/feasibility-capital/create', [App\Http\Controllers\FeasibilityController::class, 'create'])->name('feasibility.create');
Route::post('portfolio/tenders/feasibility-capital', [App\Http\Controllers\FeasibilityController::class, 'store'])->name('feasibility.store');
// Edit / Update / Delete
Route::get('portfolio/tenders/feasibility-capital/{id}/edit', [App\Http\Controllers\FeasibilityController::class, 'edit'])->name('feasibility.edit');
Route::put('portfolio/tenders/feasibility-capital/{id}', [App\Http\Controllers\FeasibilityController::class, 'update'])->name('feasibility.update');
Route::delete('portfolio/tenders/feasibility-capital/{id}', [App\Http\Controllers\FeasibilityController::class, 'destroy'])->name('feasibility.destroy');
Route::get('portfolio/tenders/feasibility-capital/{id}', [App\Http\Controllers\FeasibilityController::class, 'show'])->name('feasibility.show');

// Trash & restore routes
Route::get('portfolio/tenders/feasibility-capital/trash', [App\Http\Controllers\FeasibilityController::class, 'trash'])->name('feasibility.trash');
Route::post('portfolio/tenders/feasibility-capital/{id}/restore', [App\Http\Controllers\FeasibilityController::class, 'restore'])->name('feasibility.restore');
Route::post('portfolio/tenders/feasibility-capital/{id}/force-delete', [App\Http\Controllers\FeasibilityController::class, 'forceDelete'])->name('feasibility.force_delete');

// Undo endpoint (used by toast button)
Route::post('portfolio/tenders/feasibility-capital/{id}/undo', [App\Http\Controllers\FeasibilityController::class, 'restore'])->name('feasibility.undo');
Route::get('portfolio/tenders/feasibility-capital/{id}/export-pdf', [App\Http\Controllers\FeasibilityController::class, 'exportPdf'])->name('feasibility.export_pdf');
Route::post('portfolio/tenders/feasibility-capital/{id}/duplicate', [App\Http\Controllers\FeasibilityController::class, 'duplicate'])->name('feasibility.duplicate');
Route::post('portfolio/tenders/feasibility-capital/{id}/recalc', [App\Http\Controllers\FeasibilityController::class, 'recalc'])->name('feasibility.recalc');
Route::post('portfolio/tenders/feasibility-capital/compare', [App\Http\Controllers\FeasibilityController::class, 'compare'])->name('feasibility.compare');
// Development helper: seed demo feasibility studies (only in local environment)
if (app()->environment('local')) {
	Route::get('dev/seed-feasibility', function() {
		// run the seed script in a separate PHP process to avoid including bootstrap twice
		$script = base_path('scripts/seed_feasibility_demo.php');
		if (!file_exists($script)) {
			return redirect()->route('feasibility.index')->with('error','Seed script bulunamadÄ±');
		}

		$php = defined('PHP_BINARY') ? PHP_BINARY : 'php';
		$cmd = $php . ' ' . escapeshellarg($script) . ' 2>&1';
		try {
			$output = @shell_exec($cmd);
		} catch (\Exception $e) {
			return redirect()->route('feasibility.index')->with('error','Seed Ã§alÄ±ÅŸtÄ±rÄ±lamadÄ±: '.$e->getMessage());
		}

		// simple success detection
		if (is_string($output) && strpos($output, 'Created study ID') !== false) {
			// Log full output for debugging
			if (function_exists('logger')) logger("dev.seed-feasibility output:\n" . $output);
			return redirect()->route('feasibility.index')->with('success','Demo fizibilite verisi eklendi');
		}

		// fallback: return output as flash (trim to avoid huge messages)
		$short = is_string($output) ? substr($output,0,1000) : 'No output';
		if (function_exists('logger')) logger("dev.seed-feasibility unexpected output:\n" . $output);
		return redirect()->route('feasibility.index')->with('error','Seed tamamlandÄ± (beklenmeyen Ã§Ä±ktÄ±), kontrol edin.')->with('dev_seed_output', $short);
	})->name('dev.seed.feasibility');
}
Route::middleware('auth')->group(function(){
	Route::post('portfolio/tenders/feasibility-capital/compare/export-csv', [App\Http\Controllers\FeasibilityController::class, 'exportCompareCsv'])->name('feasibility.compare.export_csv');
	Route::post('portfolio/tenders/feasibility-capital/compare/export-pdf', [App\Http\Controllers\FeasibilityController::class, 'exportComparePdf'])->name('feasibility.compare.export_pdf');
});
// Change Orders (Ek Talepler)
Route::middleware('auth')->group(function(){
	Route::get('change-orders', [App\Http\Controllers\ChangeOrderController::class, 'index'])->name('change_orders.index');
	Route::get('change-orders/create', [App\Http\Controllers\ChangeOrderController::class, 'create'])->name('change_orders.create');
	Route::post('change-orders', [App\Http\Controllers\ChangeOrderController::class, 'store'])->name('change_orders.store');
	Route::post('change-orders/{change_order}/approve', [App\Http\Controllers\ChangeOrderController::class, 'approve'])->name('change_orders.approve');
});

// Tender package preparation (Ä°hale Paketi HazÄ±rla)
// Admin operations protected by auth
Route::middleware('auth')->group(function(){
	Route::get('portfolio/tenders/package-prepare', [App\Http\Controllers\TenderPackageController::class, 'index'])->name('tenders.package.prepare');
	Route::get('portfolio/tenders/package-prepare/create', [App\Http\Controllers\TenderPackageController::class, 'create'])->name('tenders.package.create');
	Route::post('portfolio/tenders/package-prepare', [App\Http\Controllers\TenderPackageController::class, 'store'])->name('tenders.package.store');
	Route::get('portfolio/tenders/package-prepare/{id}', [App\Http\Controllers\TenderPackageController::class, 'show'])->name('tenders.package.show');
	Route::get('portfolio/tenders/package-prepare/{id}/edit', [App\Http\Controllers\TenderPackageController::class, 'edit'])->name('tenders.package.edit');
	Route::put('portfolio/tenders/package-prepare/{id}', [App\Http\Controllers\TenderPackageController::class, 'update'])->name('tenders.package.update');
	Route::post('portfolio/tenders/package-prepare/{id}/publish', [App\Http\Controllers\TenderPackageController::class, 'publish'])->name('tenders.package.publish');
	Route::get('portfolio/tenders/package-prepare/{id}/bids', [App\Http\Controllers\TenderPackageController::class, 'bids'])->name('tenders.package.bids');
	Route::get('portfolio/tenders/package-prepare/{id}/bids/export-csv', [App\Http\Controllers\TenderPackageController::class, 'exportBidsCsv'])->name('tenders.package.bids.export_csv');
	// Signed download for bid uploaded files
	Route::get('portfolio/tenders/package-prepare/{id}/bids/download', [App\Http\Controllers\TenderPackageController::class, 'downloadBidFile'])->name('tenders.package.bids.download')->middleware('signed');
	// Bid comparison UI and CSV import
	Route::get('portfolio/tenders/package-prepare/{id}/bids/compare', [App\Http\Controllers\TenderPackageController::class, 'compareForm'])->name('tenders.package.bids.compare');
	Route::post('portfolio/tenders/package-prepare/{id}/bids/compare', [App\Http\Controllers\TenderPackageController::class, 'compareProcess'])->name('tenders.package.bids.compare_process');

	// Bid management dashboard and leveling
	Route::get('portfolio/tenders/bid-management', [App\Http\Controllers\TenderManagementController::class, 'index'])->name('tenders.bid.management');
	Route::get('portfolio/tenders/bid-management/{id}', [App\Http\Controllers\TenderManagementController::class, 'leveling'])->name('tenders.bid.leveling');
	Route::post('portfolio/tenders/bid-management/{id}/normalize', [App\Http\Controllers\TenderManagementController::class, 'normalize'])->name('tenders.bid.normalize');

	// Dev helper: seed a demo bid into a tender package (admin/test only)
	Route::post('portfolio/tenders/package-prepare/{id}/seed-demo', [App\Http\Controllers\TenderPackageController::class, 'seedDemoBid'])->name('tenders.package.seed_demo');
	Route::post('portfolio/tenders/bid-management/{id}/save-mappings', [App\Http\Controllers\TenderManagementController::class, 'saveMappings'])->name('tenders.bid.save_mappings');
});

// Signed temporary access link for bidders (public)
Route::get('portfolio/tenders/package-prepare/{id}/access', [App\Http\Controllers\TenderPackageController::class, 'access'])->name('tenders.package.access')->middleware('signed');
// Bid submission (must be via signed link so bidder is validated)
Route::post('portfolio/tenders/package-prepare/{id}/submit-bid', [App\Http\Controllers\TenderPackageController::class, 'submitBid'])->name('tenders.package.submit_bid')->middleware('signed');

// Resource routes for admin CRUD (protected by controller middleware)
Route::resource('projects', App\Http\Controllers\ProjectController::class)->except(['index','show']);

Route::get('/', [App\Http\Controllers\HomeController::class, 'root']);

// Finance & Accounting placeholder routes (added for sidebar links)
Route::get('finance/dashboard', [App\Http\Controllers\FinanceController::class, 'dashboard'])->name('finance.dashboard');

// Accounts
Route::get('finance/accounts/customers', [App\Http\Controllers\FinanceController::class, 'customers'])->name('finance.accounts.customers');
// Customer CRUD (create/edit/delete)
Route::get('finance/accounts/customers/create', [App\Http\Controllers\FinanceController::class, 'customerCreate'])->name('finance.accounts.customers.create');
Route::post('finance/accounts/customers', [App\Http\Controllers\FinanceController::class, 'customerStore'])->name('finance.accounts.customers.store');
Route::get('finance/accounts/customers/{id}/edit', [App\Http\Controllers\FinanceController::class, 'customerEdit'])->name('finance.accounts.customers.edit')->where('id','[0-9]+');
Route::put('finance/accounts/customers/{id}', [App\Http\Controllers\FinanceController::class, 'customerUpdate'])->name('finance.accounts.customers.update')->where('id','[0-9]+');
Route::delete('finance/accounts/customers/{id}', [App\Http\Controllers\FinanceController::class, 'customerDestroy'])->name('finance.accounts.customers.destroy')->where('id','[0-9]+');
Route::get('finance/accounts/customers/{id}', [App\Http\Controllers\FinanceController::class, 'customerShow'])->name('finance.accounts.customers.show')->where('id', '[0-9]+');
Route::post('finance/accounts/customers/{id}/reconcile', [App\Http\Controllers\FinanceController::class, 'sendReconciliation'])->name('finance.accounts.customers.reconcile')->where('id', '[0-9]+');
Route::post('finance/accounts/invoices/{invoice}/receipts', [App\Http\Controllers\FinanceController::class, 'storeReceipt'])->name('finance.accounts.invoices.receipts.store')->where('invoice', '[0-9]+');
Route::post('finance/accounts/invoices/receipts', [App\Http\Controllers\FinanceController::class, 'storeReceipt'])->name('finance.accounts.invoices.receipts.store.body');
Route::get('finance/accounts/customers/{id}/reconcile/download', [App\Http\Controllers\FinanceController::class, 'downloadReconciliation'])->name('finance.accounts.customers.reconcile.download')->where('id','[0-9]+');
Route::get('finance/accounts/suppliers', [App\Http\Controllers\FinanceController::class, 'suppliers'])->name('finance.accounts.suppliers');
Route::get('finance/accounts/vouchers', [App\Http\Controllers\FinanceController::class, 'vouchers'])->name('finance.accounts.vouchers');
Route::post('finance/accounts/vouchers/store', [App\Http\Controllers\FinanceController::class, 'vouchersStore'])->name('finance.accounts.vouchers.store');
Route::get('finance/accounts/vouchers/list', [App\Http\Controllers\FinanceController::class, 'vouchersList'])->name('finance.accounts.vouchers.list');
Route::get('finance/accounts/vouchers/search-parties', [App\Http\Controllers\FinanceController::class, 'vouchersSearchParties'])->name('finance.accounts.vouchers.search-parties');
Route::get('finance/accounts/vouchers/invoice-suggest', [App\Http\Controllers\FinanceController::class, 'vouchersInvoiceSuggest'])->name('finance.accounts.vouchers.invoice-suggest');
// Show single voucher details (simple viewer for linking from account movements)
Route::get('finance/accounts/vouchers/{id}/show', [App\Http\Controllers\FinanceController::class, 'voucherShow'])->name('finance.accounts.vouchers.show')->where('id','[0-9]+');
Route::delete('finance/accounts/vouchers/{id}', [App\Http\Controllers\FinanceController::class, 'vouchersDestroy'])->name('finance.accounts.vouchers.destroy');
Route::put('finance/accounts/vouchers/{id}', [App\Http\Controllers\FinanceController::class, 'vouchersUpdate'])->name('finance.accounts.vouchers.update');
Route::post('finance/accounts/vouchers/bulk-delete', [App\Http\Controllers\FinanceController::class, 'vouchersBulkDestroy'])->name('finance.accounts.vouchers.bulk-delete');
Route::get('finance/accounts/reports', [App\Http\Controllers\FinanceController::class, 'accountReports'])->name('finance.accounts.reports');
// AJAX endpoints for account reports
Route::get('finance/accounts/reports/data', [App\Http\Controllers\FinanceController::class, 'reportData'])->name('finance.accounts.reports.data');

// Protected actions: download/export
Route::middleware('auth')->group(function(){
	Route::get('finance/accounts/reports/download', [App\Http\Controllers\FinanceController::class, 'reportDownload'])->name('finance.accounts.reports.download');
	// Export (CSV/Excel) for reports
	Route::get('finance/accounts/reports/export', [App\Http\Controllers\FinanceController::class, 'reportExport'])->name('finance.accounts.reports.export');
});

// Cash & Bank
Route::get('finance/cash/accounts', [App\Http\Controllers\FinanceController::class, 'cashAccounts'])->name('finance.cash.accounts');
Route::post('finance/cash/accounts', [App\Http\Controllers\FinanceController::class, 'cashAccountsStore'])->name('finance.cash.accounts.store');
Route::get('finance/cash/accounts/data', [App\Http\Controllers\FinanceController::class, 'cashAccountsData'])->name('finance.cash.accounts.data');
Route::get('finance/cash/movements', [App\Http\Controllers\FinanceController::class, 'cashMovements'])->name('finance.cash.movements');
Route::get('finance/cash/accounts/{id}/movements', [App\Http\Controllers\FinanceController::class, 'cashAccountMovements'])->name('finance.cash.accounts.movements')->where('id','[0-9]+');
Route::get('finance/cash/accounts/{id}/movements/export', [App\Http\Controllers\FinanceController::class, 'cashAccountMovementsExport'])->name('finance.cash.accounts.movements.export')->where('id','[0-9]+');
Route::middleware('auth')->group(function(){
	Route::get('finance/cash/transfers', [App\Http\Controllers\FinanceController::class, 'cashTransfers'])->name('finance.cash.transfers');
	Route::get('finance/cash/transfers/data', [App\Http\Controllers\FinanceController::class, 'cashTransfersData'])->name('finance.cash.transfers.data');
	Route::get('finance/cash/transfers/pending', [App\Http\Controllers\FinanceController::class, 'cashTransfersPending'])->name('finance.cash.transfers.pending');
	Route::post('finance/cash/transfers/transfer', [App\Http\Controllers\FinanceController::class, 'cashTransfersStoreTransfer'])->name('finance.cash.transfers.transfer');
	Route::post('finance/cash/transfers/eft', [App\Http\Controllers\FinanceController::class, 'cashTransfersStoreEft'])->name('finance.cash.transfers.eft');
	Route::post('finance/cash/transfers/{id}/approve', [App\Http\Controllers\FinanceController::class, 'cashTransfersApprove'])->name('finance.cash.transfers.approve')->where('id','.*');
});
Route::get('finance/cash/mutabakat', [App\Http\Controllers\FinanceController::class, 'cashMutabakat'])->name('finance.cash.mutabakat');
// Bank reconciliation endpoints
Route::post('finance/cash/mutabakat/upload', [App\Http\Controllers\FinanceController::class, 'uploadBankStatement'])->name('finance.cash.mutabakat.upload');
Route::post('finance/cash/mutabakat/auto-match', [App\Http\Controllers\FinanceController::class, 'mutabakatAutoMatch'])->name('finance.cash.mutabakat.automatch');
Route::post('finance/cash/mutabakat/create-voucher', [App\Http\Controllers\FinanceController::class, 'mutabakatCreateVoucher'])->name('finance.cash.mutabakat.create_voucher');
Route::get('finance/cash/pos', [App\Http\Controllers\FinanceController::class, 'cashPos'])->name('finance.cash.pos');
Route::get('finance/cash/pos/terminals', [App\Http\Controllers\FinanceController::class, 'getPosTerminals'])->name('finance.cash.pos.terminals');
Route::get('finance/cash/pos/transactions', [App\Http\Controllers\FinanceController::class, 'getPosTransactions'])->name('finance.cash.pos.transactions');
Route::get('finance/cash/pos/stats', [App\Http\Controllers\FinanceController::class, 'getPosStats'])->name('finance.cash.pos.stats');
Route::put('finance/cash/pos/terminals/{terminalId}/status', [App\Http\Controllers\FinanceController::class, 'updateTerminalStatus'])->name('finance.cash.pos.update_status');
Route::post('finance/cash/pos/reports', [App\Http\Controllers\FinanceController::class, 'generatePosReport'])->name('finance.cash.pos.reports');
Route::get('finance/cash/currency', [App\Http\Controllers\FinanceController::class, 'cashCurrency'])->name('finance.cash.currency');

// Cheques
Route::get('finance/cheques/customers', [App\Http\Controllers\FinanceController::class, 'chequesCustomers'])->name('finance.cheques.customers');
Route::get('finance/cheques/own', [App\Http\Controllers\FinanceController::class, 'chequesOwn'])->name('finance.cheques.own');

// Stock
Route::get('finance/stock/cards', [App\Http\Controllers\FinanceController::class, 'stockCards'])->name('finance.stock.cards');
Route::post('finance/stock/cards', [App\Http\Controllers\FinanceController::class, 'storeStockCard'])->name('finance.stock.cards.store');
Route::get('finance/stock/cards/{id}', [App\Http\Controllers\FinanceController::class, 'showStockCard'])->name('finance.stock.cards.show');
Route::put('finance/stock/cards/{id}', [App\Http\Controllers\FinanceController::class, 'updateStockCard'])->name('finance.stock.cards.update');
Route::delete('finance/stock/cards/{id}', [App\Http\Controllers\FinanceController::class, 'destroyStockCard'])->name('finance.stock.cards.destroy');
Route::get('finance/stock/cards/{id}/movements', [App\Http\Controllers\FinanceController::class, 'getStockMovements'])->name('finance.stock.cards.movements');
Route::post('finance/stock/movements', [App\Http\Controllers\FinanceController::class, 'storeStockMovement'])->name('finance.stock.movements.store');
Route::get('finance/stock/export', [App\Http\Controllers\FinanceController::class, 'exportStock'])->name('finance.stock.export');

Route::get('finance/stock/locations', [App\Http\Controllers\FinanceController::class, 'stockLocations'])->name('finance.stock.locations');
Route::post('finance/stock/locations', [App\Http\Controllers\FinanceController::class, 'stockLocationStore'])->name('finance.stock.locations.store');
Route::get('finance/stock/locations/{id}', [App\Http\Controllers\FinanceController::class, 'stockLocationShow'])->name('finance.stock.locations.show');
Route::put('finance/stock/locations/{id}', [App\Http\Controllers\FinanceController::class, 'stockLocationUpdate'])->name('finance.stock.locations.update');
Route::delete('finance/stock/locations/{id}', [App\Http\Controllers\FinanceController::class, 'stockLocationDestroy'])->name('finance.stock.locations.destroy');
Route::patch('finance/stock/locations/{id}/toggle-status', [App\Http\Controllers\FinanceController::class, 'stockLocationToggleStatus'])->name('finance.stock.locations.toggle-status');

Route::get('finance/stock/movements', [App\Http\Controllers\FinanceController::class, 'stockMovements'])->name('finance.stock.movements');
Route::get('finance/stock/movements/{id}', [App\Http\Controllers\FinanceController::class, 'showStockMovement'])->name('finance.stock.movements.show');
Route::get('finance/stock/movements/{id}/print', [App\Http\Controllers\FinanceController::class, 'printStockMovement'])->name('finance.stock.movements.print');
Route::get('finance/stock/reports', [App\Http\Controllers\FinanceController::class, 'stockReports'])->name('finance.stock.reports');
Route::post('finance/stock/send-low-stock-alert', [App\Http\Controllers\FinanceController::class, 'sendLowStockAlert'])->name('finance.stock.send-low-stock-alert');
Route::get('finance/stock/reports/export', [App\Http\Controllers\FinanceController::class, 'exportStockReports'])->name('finance.stock.reports.export');

// HR / Payroll
Route::get('finance/hr/expenses', [App\Http\Controllers\FinanceController::class, 'hrExpenses'])->name('finance.hr.expenses');
Route::get('finance/hr/expenses/export', [App\Http\Controllers\FinanceController::class, 'exportHrExpenses'])->name('finance.hr.expenses.export');
Route::post('finance/hr/expenses/{id}/approve', [App\Http\Controllers\FinanceController::class, 'approveHrExpense'])->name('finance.hr.expenses.approve');
Route::post('finance/hr/expenses/{id}/reject', [App\Http\Controllers\FinanceController::class, 'rejectHrExpense'])->name('finance.hr.expenses.reject');
Route::get('finance/hr/advances', [App\Http\Controllers\FinanceController::class, 'hrAdvances'])->name('finance.hr.advances');
Route::post('finance/hr/advances/create', [App\Http\Controllers\FinanceController::class, 'createAdvance'])->name('finance.hr.advances.create');
Route::post('finance/hr/advances/{id}/approve', [App\Http\Controllers\FinanceController::class, 'approveAdvance'])->name('finance.hr.advances.approve');
Route::post('finance/hr/advances/{id}/reject', [App\Http\Controllers\FinanceController::class, 'rejectAdvance'])->name('finance.hr.advances.reject');
Route::post('finance/hr/advances/{id}/payment', [App\Http\Controllers\FinanceController::class, 'addAdvancePayment'])->name('finance.hr.advances.payment');
Route::get('finance/hr/advances/{id}/details', [App\Http\Controllers\FinanceController::class, 'getAdvanceDetails'])->name('finance.hr.advances.details');
Route::get('finance/hr/payroll', [App\Http\Controllers\FinanceController::class, 'hrPayroll'])->name('finance.hr.payroll');
Route::post('finance/hr/payroll/{id}/approve', [App\Http\Controllers\FinanceController::class, 'approvePayroll'])->name('finance.hr.payroll.approve');
Route::post('finance/hr/payroll/bulk-action', [App\Http\Controllers\FinanceController::class, 'bulkPayrollAction'])->name('finance.hr.payroll.bulk');
Route::get('finance/hr/payroll/{id}/details', [App\Http\Controllers\FinanceController::class, 'getPayrollDetails'])->name('finance.hr.payroll.details');
Route::post('finance/hr/payroll/{id}/update', [App\Http\Controllers\FinanceController::class, 'updatePayroll'])->name('finance.hr.payroll.update');

// Expenses
Route::get('finance/expenses/general', [App\Http\Controllers\FinanceController::class, 'expensesGeneral'])->name('finance.expenses.general');

// Assets
Route::get('finance/assets/list', [App\Http\Controllers\FinanceController::class, 'assetsList'])->name('finance.assets.list');
Route::get('finance/assets/amortization', [App\Http\Controllers\FinanceController::class, 'assetsAmortization'])->name('finance.assets.amortization');

// Budget
Route::get('finance/budget/company', [App\Http\Controllers\FinanceController::class, 'budgetCompany'])->name('finance.budget.company');
Route::get('finance/budget/loans', [App\Http\Controllers\FinanceController::class, 'budgetLoans'])->name('finance.budget.loans');
Route::get('finance/budget/guarantees', [App\Http\Controllers\FinanceController::class, 'budgetGuarantees'])->name('finance.budget.guarantees');

// E-Docs
Route::get('finance/edoc/efatura', [App\Http\Controllers\FinanceController::class, 'edocEfatura'])->name('finance.edoc.efatura');
Route::get('finance/edoc/earshiv', [App\Http\Controllers\FinanceController::class, 'edocEarshiv'])->name('finance.edoc.earshiv');
Route::get('finance/edoc/eirsaliye', [App\Http\Controllers\FinanceController::class, 'edocEirsaliye'])->name('finance.edoc.eirsaliye');
// Dev preview route for tender package create (only enable when APP_DEBUG=true)
// Public dev preview (development convenience) - render the create view directly and omit auth middleware
Route::get('portfolio/tenders/package-prepare/preview', function(){
	// For local preview, auto-login as user id 1 if exists (development convenience)
	try {
		if (class_exists(\App\Models\User::class) && \App\Models\User::find(1)) {
			Auth::loginUsingId(1);
		}
	} catch (\Exception $e) {
		// ignore
	}
	$projects = App\Models\Project::all();
	return view('portfolio.tenders.package-create', compact('projects'));
})->name('tenders.package.preview');
// Contracts management (SÃ¶zleÅŸme YÃ¶netimi)
Route::get('contracts/management', [App\Http\Controllers\ContractsController::class, 'management'])->name('contracts.management');
// Subcontractor management (TaÅŸeron YÃ¶netimi)
Route::middleware('auth')->group(function(){
	Route::get('subcontractor/companies', [App\Http\Controllers\SubcontractorController::class, 'companies'])->name('subcontractor.companies');
	Route::get('subcontractor/directory', [App\Http\Controllers\SubcontractorController::class, 'index'])->name('subcontractor.index');
	Route::get('subcontractor/contracts', [App\Http\Controllers\SubcontractorController::class, 'contracts'])->name('subcontractor.contracts');
	Route::get('subcontractor/contracts/list', [App\Http\Controllers\SubcontractorController::class, 'contractIndex'])->name('subcontractor.contracts.index');
	Route::get('subcontractor/contracts/{id}', [App\Http\Controllers\SubcontractorController::class, 'contractShow'])->name('subcontractor.contracts.show');
	Route::get('subcontractor/progress/new', [App\Http\Controllers\SubcontractorController::class, 'newProgress'])->name('subcontractor.progress.new');
	Route::post('subcontractor/progress', [App\Http\Controllers\SubcontractorController::class, 'storeProgress'])->name('subcontractor.progress.store');
	Route::get('subcontractor/progress/approval', [App\Http\Controllers\SubcontractorController::class, 'progressApproval'])->name('subcontractor.progress.approval');
	Route::get('subcontractor/progress/approval/{id}', [App\Http\Controllers\SubcontractorController::class, 'progressApprovalDetail'])->name('subcontractor.progress.approval.detail');
	Route::post('subcontractor/progress/approval/{id}', [App\Http\Controllers\SubcontractorController::class, 'processApproval'])->name('subcontractor.progress.approval.process');
	Route::get('subcontractor/progress/payment', [App\Http\Controllers\SubcontractorController::class, 'progressPayment'])->name('subcontractor.progress.payment');
	Route::get('subcontractor/payments', [App\Http\Controllers\SubcontractorController::class, 'payments'])->name('subcontractor.payments');
	Route::get('subcontractor/payments/{id}', [App\Http\Controllers\SubcontractorController::class, 'showPayment'])->name('subcontractor.payments.show');
	Route::get('subcontractor/{id}', [App\Http\Controllers\SubcontractorController::class, 'show'])->name('subcontractor.show');
});
// Project document control (MVP)
Route::get('projects/documents', [App\Http\Controllers\DocumentController::class, 'all'])->name('projects.documents.all');
// Project planning and timeline (Planning & Timeline) - interactive module
Route::get('projects/planning', [App\Http\Controllers\ProjectPlanningController::class, 'index'])->name('projects.planning');

// TEST: GeÃ§ici olarak auth olmadan test edilebilir API routes
Route::get('projects/{project}/planning/tasks', [App\Http\Controllers\ProjectPlanningController::class, 'tasks'])->name('projects.planning.tasks');
Route::get('projects/{project}/planning/statistics', [App\Http\Controllers\ProjectPlanningController::class, 'getProjectStatistics'])->name('projects.planning.statistics');
Route::get('projects/{project}/planning/export', [App\Http\Controllers\ProjectPlanningController::class, 'exportTasks'])->name('projects.planning.export');
Route::get('projects/{project}/planning/risk-analysis', [App\Http\Controllers\ProjectPlanningController::class, 'riskAnalysis'])->name('projects.planning.risk_analysis');
Route::get('projects/{project}/planning/resource-report', [App\Http\Controllers\ProjectPlanningController::class, 'resourceReport'])->name('projects.planning.resource_report');

Route::middleware('auth')->group(function(){
	Route::put('projects/{project}/planning/tasks/{task}', [App\Http\Controllers\ProjectPlanningController::class, 'updateTask'])->name('projects.planning.tasks.update');
	Route::get('projects/{project}/planning/tasks/{task}/details', [App\Http\Controllers\ProjectPlanningController::class, 'getTaskDetails'])->name('projects.planning.tasks.details');

	// Baseline endpoints
	Route::get('projects/{project}/planning/baselines', [App\Http\Controllers\ProjectPlanningController::class, 'listBaselines'])->name('projects.planning.baselines.list');
	Route::post('projects/{project}/planning/baselines', [App\Http\Controllers\ProjectPlanningController::class, 'createBaseline'])->name('projects.planning.baselines.create');
	Route::get('projects/{project}/planning/baselines/{baseline}/compare', [App\Http\Controllers\ProjectPlanningController::class, 'compareBaseline'])->name('projects.planning.baselines.compare');

	// Task management endpoints
	Route::post('projects/{project}/planning/tasks', [App\Http\Controllers\ProjectPlanningController::class, 'createTask'])->name('projects.planning.tasks.create');
	Route::delete('projects/{project}/planning/tasks/{task}', [App\Http\Controllers\ProjectPlanningController::class, 'deleteTask'])->name('projects.planning.tasks.delete');
	Route::patch('projects/{project}/planning/tasks/{task}/progress', [App\Http\Controllers\ProjectPlanningController::class, 'updateTaskProgress'])->name('projects.planning.tasks.progress');
	Route::patch('projects/{project}/planning/tasks/bulk-update', [App\Http\Controllers\ProjectPlanningController::class, 'bulkUpdateTasks'])->name('projects.planning.tasks.bulk_update');

	// Statistics and analysis endpoints
	Route::get('projects/{project}/planning/timeline-data', [App\Http\Controllers\ProjectPlanningController::class, 'getTimelineData'])->name('projects.planning.timeline_data');
	Route::get('projects/{project}/planning/resource-histogram', [App\Http\Controllers\ProjectPlanningController::class, 'getResourceHistogram'])->name('projects.planning.resource_histogram');
	Route::get('projects/{project}/planning/two-week-lookahead', [App\Http\Controllers\ProjectPlanningController::class, 'getTwoWeekLookahead'])->name('projects.planning.two_week_lookahead');

	// Communication module
	Route::get('projects/communication', [App\Http\Controllers\CommunicationController::class, 'dashboard'])->name('projects.communication.dashboard');
	Route::get('projects/{project}/communication/rfis', [App\Http\Controllers\CommunicationController::class, 'rfiList'])->name('projects.communication.rfis');
	Route::post('projects/{project}/communication/rfis', [App\Http\Controllers\CommunicationController::class, 'rfiStore'])->name('projects.communication.rfis.store');
	Route::get('projects/{project}/communication/rfis/{rfi}/edit', [App\Http\Controllers\CommunicationController::class, 'rfiEdit'])->name('projects.communication.rfis.edit');
	Route::put('projects/{project}/communication/rfis/{rfi}', [App\Http\Controllers\CommunicationController::class, 'rfiUpdate'])->name('projects.communication.rfis.update');
	Route::delete('projects/{project}/communication/rfis/{rfi}', [App\Http\Controllers\CommunicationController::class, 'rfiDestroy'])->name('projects.communication.rfis.destroy');
	Route::get('projects/{project}/communication/submittals', [App\Http\Controllers\CommunicationController::class, 'submittalList'])->name('projects.communication.submittals');
	Route::post('projects/{project}/communication/submittals', [App\Http\Controllers\CommunicationController::class, 'submittalStore'])->name('projects.communication.submittals.store');
	Route::post('projects/{project}/communication/submittals/{submittal}/review', [App\Http\Controllers\CommunicationController::class, 'submittalReview'])->name('projects.communication.submittals.review');
	Route::get('projects/{project}/communication/meetings', [App\Http\Controllers\CommunicationController::class, 'meetingList'])->name('projects.communication.meetings');
	Route::post('projects/{project}/communication/meetings', [App\Http\Controllers\CommunicationController::class, 'meetingStore'])->name('projects.communication.meetings.store');
	Route::get('projects/{project}/communication/meetings/{meeting}', [App\Http\Controllers\CommunicationController::class, 'meetingShow'])->name('projects.communication.meetings.show');
	Route::get('projects/{project}/communication/meetings/{meeting}/edit', [App\Http\Controllers\CommunicationController::class, 'meetingEdit'])->name('projects.communication.meetings.edit');
	Route::put('projects/{project}/communication/meetings/{meeting}', [App\Http\Controllers\CommunicationController::class, 'meetingUpdate'])->name('projects.communication.meetings.update');
	Route::delete('projects/{project}/communication/meetings/{meeting}', [App\Http\Controllers\CommunicationController::class, 'meetingDelete'])->name('projects.communication.meetings.destroy');
	Route::post('projects/{project}/communication/action-items', [App\Http\Controllers\CommunicationController::class, 'actionStore'])->name('projects.communication.action.store');

	// Signed download for communication attachments (rfi/submittal/meeting/action)
	Route::get('projects/{project}/communication/{type}/{id}/download/{file}', [App\Http\Controllers\CommunicationController::class, 'downloadAttachment'])->name('projects.communication.attachment.download')->middleware('signed');

	// helper to get a temporary signed url (returns JSON) - no signed middleware because this endpoint creates the signed url
	Route::get('projects/{project}/communication/{type}/{id}/signed-url/{file}', [App\Http\Controllers\CommunicationController::class, 'signedAttachmentUrl'])->name('projects.communication.attachment.signed');

	// Equipment Management (Ekipman ParkÄ±)
	Route::prefix('resources/equipment')->name('resources.equipment.')->group(function() {
		Route::get('/', [App\Http\Controllers\EquipmentController::class, 'dashboard'])->name('dashboard');
		Route::get('/list', [App\Http\Controllers\EquipmentController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\EquipmentController::class, 'create'])->name('create');
		Route::post('/', [App\Http\Controllers\EquipmentController::class, 'store'])->name('store');
		Route::get('/{equipment}', [App\Http\Controllers\EquipmentController::class, 'show'])->name('show');
		Route::get('/{equipment}/edit', [App\Http\Controllers\EquipmentController::class, 'edit'])->name('edit');
		Route::put('/{equipment}', [App\Http\Controllers\EquipmentController::class, 'update'])->name('update');
		Route::delete('/{equipment}', [App\Http\Controllers\EquipmentController::class, 'destroy'])->name('destroy');

		// Maintenance
		Route::get('/{equipment}/maintenance', [App\Http\Controllers\EquipmentController::class, 'maintenance'])->name('maintenance');
		Route::post('/{equipment}/maintenance', [App\Http\Controllers\EquipmentController::class, 'storeMaintenance'])->name('maintenance.store');

		// Documents
		Route::post('/{equipment}/documents', [App\Http\Controllers\EquipmentController::class, 'storeDocument'])->name('documents.store');
	});
});

// BIM / Model YÃ¶netimi
// Public signed file delivery for viewers (keeps file protected but allows temporary access)
Route::get('bim/models/{id}/file/public', [App\Http\Controllers\BimModelController::class, 'filePublic'])->name('bim.models.file.public')->middleware('signed');

Route::middleware('auth')->group(function(){
	Route::get('bim/models', [App\Http\Controllers\BimModelController::class, 'index'])->name('bim.models.index');
	Route::get('bim/models/upload', [App\Http\Controllers\BimModelController::class, 'uploadForm'])->name('bim.models.upload');
	Route::post('bim/models', [App\Http\Controllers\BimModelController::class, 'store'])->name('bim.models.store');
	Route::get('bim/models/{id}/viewer', [App\Http\Controllers\BimModelController::class, 'viewer'])->name('bim.models.viewer');
	Route::get('bim/models/{id}/file', [App\Http\Controllers\BimModelController::class, 'file'])->name('bim.models.file');
	Route::post('bim/models/{id}/convert-rvt', [App\Http\Controllers\BimModelController::class, 'convertRvt'])->name('bim.models.convert_rvt');
	Route::post('bim/clash-tests', [App\Http\Controllers\BimModelController::class, 'createClashTest'])->name('bim.clashtests.create');
	Route::get('bim/clash-tests/{id}', [App\Http\Controllers\BimModelController::class, 'clashDetail'])->name('bim.clashtests.detail');

	// BIM SimÃ¼lasyonlarÄ± (4D / 5D)
	Route::get('bim/simulations', [App\Http\Controllers\BimSimulationController::class, 'index'])->name('bim.simulations.index');
	Route::get('bim/simulations/create', [App\Http\Controllers\BimSimulationController::class, 'create'])->name('bim.simulations.create');
	Route::post('bim/simulations', [App\Http\Controllers\BimSimulationController::class, 'store'])->name('bim.simulations.store');
	Route::get('bim/simulations/{id}', [App\Http\Controllers\BimSimulationController::class, 'show'])->name('bim.simulations.show');
	Route::get('bim/simulations/{id}/play', [App\Http\Controllers\BimSimulationController::class, 'play'])->name('bim.simulations.play');
	// dedicated endpoints for 4D and 5D viewers
	Route::get('bim/simulations/{id}/4d', [App\Http\Controllers\BimSimulationController::class, 'show4D'])->name('bim.simulations.4d');
	Route::get('bim/simulations/{id}/5d', [App\Http\Controllers\BimSimulationController::class, 'show5D'])->name('bim.simulations.5d');
	Route::post('bim/simulations/{id}/map-elements', [App\Http\Controllers\BimSimulationController::class, 'saveMapping'])->name('bim.simulations.map_elements');
	Route::post('bim/simulations/{id}/compute-costs', [App\Http\Controllers\BimSimulationController::class, 'recomputeCosts'])->name('bim.simulations.recompute_costs');
});
Route::middleware('auth')->group(function(){
	Route::get('projects/{project}/documents', [App\Http\Controllers\DocumentController::class, 'index'])->name('projects.documents.index');
	Route::get('projects/{project}/documents/upload', [App\Http\Controllers\DocumentController::class, 'uploadForm'])->name('projects.documents.upload');
	Route::post('projects/{project}/documents', [App\Http\Controllers\DocumentController::class, 'store'])->name('projects.documents.store');
	Route::get('projects/{project}/documents/{id}/show', [App\Http\Controllers\DocumentController::class, 'showFile'])->name('projects.documents.show');
	Route::get('projects/{project}/documents/{id}/viewer', [App\Http\Controllers\DocumentController::class, 'viewer'])->name('projects.documents.viewer');
	Route::get('projects/{project}/documents/{id}/history', [App\Http\Controllers\DocumentController::class, 'history'])->name('projects.documents.history');

	// Contract-scoped documents
	Route::get('contracts/{contract}/documents', [App\Http\Controllers\DocumentController::class, 'contractIndex'])->name('contracts.documents.index');
	Route::post('contracts/{contract}/documents', [App\Http\Controllers\DocumentController::class, 'storeForContract'])->name('contracts.documents.store');
	Route::get('contracts/{contract}/documents/{id}/show', [App\Http\Controllers\DocumentController::class, 'showForContract'])->name('contracts.documents.show');
	Route::get('contracts/{contract}/documents/{id}/viewer', [App\Http\Controllers\DocumentController::class, 'viewerForContract'])->name('contracts.documents.viewer');
});

// Supply / Vendors explicit routes (server-side rendered pages & APIs)
Route::middleware('auth')->group(function(){
	Route::get('supply/vendors', [App\Http\Controllers\SupplyController::class, 'vendors'])->name('supply.vendors');
	Route::get('supply/vendors/{vendor}', [App\Http\Controllers\SupplyController::class, 'show'])->name('supply.vendors.show');
	Route::post('supply/vendors', [App\Http\Controllers\SupplyController::class, 'store'])->name('supply.vendors.store');
	Route::post('supply/vendors/{vendor}/reviews', [App\Http\Controllers\SupplyController::class, 'storeReview'])->name('supply.vendors.reviews.store');
	Route::post('supply/vendors/{vendor}/documents', [App\Http\Controllers\SupplyController::class, 'uploadDocument'])->name('supply.vendors.documents.upload');
	// Simple JSON list for AJAX filtering
	Route::get('api/supply/vendors', [App\Http\Controllers\SupplyController::class, 'apiList'])->name('api.supply.vendors');
	Route::get('api/supply/vendors/stats', [App\Http\Controllers\SupplyController::class, 'apiStats'])->name('api.supply.vendors.stats');

		// Mirror routes under Finance (finance.accounts.suppliers.*)
		Route::get('finance/accounts/suppliers', [App\Http\Controllers\SupplyController::class, 'vendors'])->name('finance.accounts.suppliers');
		Route::get('finance/accounts/suppliers/{vendor}', [App\Http\Controllers\SupplyController::class, 'show'])->name('finance.accounts.suppliers.show');
		Route::post('finance/accounts/suppliers', [App\Http\Controllers\SupplyController::class, 'store'])->name('finance.accounts.suppliers.store');
		Route::post('finance/accounts/suppliers/{vendor}/reviews', [App\Http\Controllers\SupplyController::class, 'storeReview'])->name('finance.accounts.suppliers.reviews.store');
		Route::post('finance/accounts/suppliers/{vendor}/documents', [App\Http\Controllers\SupplyController::class, 'uploadDocument'])->name('finance.accounts.suppliers.documents.upload');
		Route::get('api/finance/suppliers', [App\Http\Controllers\SupplyController::class, 'apiList'])->name('api.finance.suppliers');
		Route::get('api/finance/suppliers/stats', [App\Http\Controllers\SupplyController::class, 'apiStats'])->name('api.finance.suppliers.stats');

	// Procurement routes
	Route::get('supply/procurement', [App\Http\Controllers\ProcurementController::class, 'index'])->name('supply.procurement');
	Route::get('supply/procurement/requisitions', [App\Http\Controllers\ProcurementController::class, 'requisitions'])->name('supply.procurement.requisitions');
	Route::get('supply/procurement/purchase-orders', [App\Http\Controllers\ProcurementController::class, 'purchaseOrders'])->name('supply.procurement.purchase_orders');
	Route::get('supply/procurement/requisitions/create', [App\Http\Controllers\ProcurementController::class, 'createRequisition'])->name('supply.procurement.requisitions.create');
	Route::post('supply/procurement/requisitions', [App\Http\Controllers\ProcurementController::class, 'storeRequisition'])->name('supply.procurement.requisitions.store');

	// Closeout routes
	Route::resource('closeout', App\Http\Controllers\CloseoutController::class);
	Route::get('closeout/{closeout}/warranty-claims/create', [App\Http\Controllers\CloseoutController::class, 'createWarrantyClaim'])->name('closeout.create-warranty-claim');
	Route::post('closeout/{closeout}/warranty-claims', [App\Http\Controllers\CloseoutController::class, 'storeWarrantyClaim'])->name('closeout.store-warranty-claim');
	Route::put('warranty-claims/{warrantyClaim}', [App\Http\Controllers\CloseoutController::class, 'updateWarrantyClaim'])->name('closeout.update-warranty-claim');
});

// Finance - supplier accounts detail route (mirror supply vendor show under finance path)
Route::middleware('auth')->group(function(){
    Route::get('finance/accounts/suppliers/{vendor}', [App\Http\Controllers\FinanceController::class, 'supplierShow'])->name('finance.accounts.suppliers.show');
});

// Facility Management routes
Route::middleware('auth')->group(function(){
	Route::get('facility', [App\Http\Controllers\FacilityController::class, 'index'])->name('facility.index');
	Route::get('facility/assets', [App\Http\Controllers\FacilityController::class, 'assets'])->name('facility.assets');
	Route::get('facility/assets/{asset}', [App\Http\Controllers\FacilityController::class, 'assetShow'])->name('facility.assets.show');
	Route::post('facility/assets/{asset}/documents', [App\Http\Controllers\FacilityController::class, 'uploadDocument'])->name('facility.assets.upload-document');
	Route::get('facility/maintenance', [App\Http\Controllers\FacilityController::class, 'maintenance'])->name('facility.maintenance');
	Route::get('facility/requests', [App\Http\Controllers\FacilityController::class, 'requests'])->name('facility.requests');
	Route::post('facility/requests', [App\Http\Controllers\FacilityController::class, 'storeRequest'])->name('facility.requests.store');
});

// Analytics & Reports routes
Route::middleware('auth')->group(function(){
	Route::get('analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
	Route::get('analytics/risk-map', [App\Http\Controllers\AnalyticsController::class, 'riskMap'])->name('analytics.risk-map');
	Route::get('analytics/benchmarking', [App\Http\Controllers\AnalyticsController::class, 'benchmarking'])->name('analytics.benchmarking');
	Route::get('analytics/profit-forecast', [App\Http\Controllers\AnalyticsController::class, 'profitForecast'])->name('analytics.profit-forecast');
	Route::get('analytics/what-if', [App\Http\Controllers\AnalyticsController::class, 'whatIf'])->name('analytics.what-if');
	Route::get('analytics/wizard', [App\Http\Controllers\AnalyticsController::class, 'wizard'])->name('analytics.wizard');
	Route::post('analytics/wizard/generate', [App\Http\Controllers\AnalyticsController::class, 'generateReport'])->name('analytics.wizard.generate');
	Route::post('analytics/wizard/save', [App\Http\Controllers\AnalyticsController::class, 'saveReport'])->name('analytics.wizard.save');
});

// AI Module routes
Route::middleware('auth')->group(function(){
	Route::get('ai-module', [App\Http\Controllers\AiModuleController::class, 'index'])->name('ai-module.index');
	Route::get('ai-module/proactive-assistant', [App\Http\Controllers\AiModuleController::class, 'proactiveAssistant'])->name('ai-module.proactive-assistant');
	Route::post('ai-module/proactive-assistant', [App\Http\Controllers\AiModuleController::class, 'proactiveAssistant'])->name('ai-module.proactive-assistant.post');
	Route::get('ai-module/risk-analysis', [App\Http\Controllers\AiModuleController::class, 'riskAnalysis'])->name('ai-module.risk-analysis');
	Route::get('ai-module/document-processing', [App\Http\Controllers\AiModuleController::class, 'documentProcessing'])->name('ai-module.document-processing');
	Route::get('ai-module/site-data-analysis', [App\Http\Controllers\AiModuleController::class, 'siteDataAnalysis'])->name('ai-module.site-data-analysis');
	Route::get('ai-module/compliance-report', [App\Http\Controllers\AiModuleController::class, 'complianceReport'])->name('ai-module.compliance-report');
	Route::get('ai-module/predictive-analytics', [App\Http\Controllers\AiModuleController::class, 'predictiveAnalytics'])->name('ai-module.predictive-analytics');
});

// Catch-all route for SPA pages (accepts slashes) - keep last
