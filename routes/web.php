<?php
// Yardım sayfası rotası
require_once base_path('routes/web-help.php');
// Proje ana listeleme route'u (projects.index)
Route::get('projects', [App\Http\Controllers\ProjectController::class, 'index'])->name('projects.index');
Route::get('projects/documents', [App\Http\Controllers\DocumentController::class, 'all'])->name('projects.documents.all');
Route::get('projects/planning', [App\Http\Controllers\ProjectPlanningController::class, 'index'])->name('projects.planning');
Route::get('projects/{project}/planning/tasks', [App\Http\Controllers\ProjectPlanningController::class, 'tasks'])->name('projects.planning.tasks');
Route::post('projects/{project}/planning/tasks', [App\Http\Controllers\ProjectPlanningController::class, 'storeTask'])->name('projects.planning.tasks.store');
Route::get('projects/communication', [App\Http\Controllers\CommunicationController::class, 'dashboard'])->name('projects.communication.dashboard');
// Global Search API routes (public - no auth required for basic search)
// Fazla kapanış parantezleri kaldırıldı
Route::get('api/search', [App\Http\Controllers\Api\SearchController::class, 'search'])->name('api.search');
Route::get('search', [App\Http\Controllers\SearchController::class, 'index'])->name('search.index');

// ─── Kullanıcı Tema Tercihi ───────────────────────────────────────────────────
Route::post('user/theme', [App\Http\Controllers\ThemeController::class, 'store'])
    ->middleware('web')
    ->name('user.theme.store');


// Subcontractor Portal routes
Route::prefix('subcontractor')->name('subcontractor.')->group(function(){
	Route::middleware('subcontractor.guest')->group(function(){
		Route::get('login', [App\Http\Controllers\SubcontractorPortal\AuthController::class, 'showLogin'])->name('login');
		Route::post('login', [App\Http\Controllers\SubcontractorPortal\AuthController::class, 'login'])->name('login.attempt');
	});

	Route::post('logout', [App\Http\Controllers\SubcontractorPortal\AuthController::class, 'logout'])->name('logout');

	Route::middleware('auth:subcontractor')->group(function(){
		Route::get('dashboard', [App\Http\Controllers\SubcontractorPortal\DashboardController::class, 'index'])->name('dashboard');

		Route::get('services', [App\Http\Controllers\SubcontractorPortal\ServiceEntryController::class, 'index'])->name('services.index');
		Route::get('services/create', [App\Http\Controllers\SubcontractorPortal\ServiceEntryController::class, 'create'])->name('services.create');
		Route::post('services', [App\Http\Controllers\SubcontractorPortal\ServiceEntryController::class, 'store'])->name('services.store');
		Route::get('services/{service}/edit', [App\Http\Controllers\SubcontractorPortal\ServiceEntryController::class, 'edit'])->name('services.edit');
		Route::put('services/{service}', [App\Http\Controllers\SubcontractorPortal\ServiceEntryController::class, 'update'])->name('services.update');

		// Public subcontractor portal routes only (management create/store handled in admin area)

	Route::get('materials', [App\Http\Controllers\SubcontractorPortal\MaterialEntryController::class, 'index'])->name('materials.index');
	Route::get('materials/create', [App\Http\Controllers\SubcontractorPortal\MaterialEntryController::class, 'create'])->name('materials.create');
	Route::post('materials', [App\Http\Controllers\SubcontractorPortal\MaterialEntryController::class, 'store'])->name('materials.store');
	Route::get('materials/{material}/edit', [App\Http\Controllers\SubcontractorPortal\MaterialEntryController::class, 'edit'])->name('materials.edit');
	Route::put('materials/{material}', [App\Http\Controllers\SubcontractorPortal\MaterialEntryController::class, 'update'])->name('materials.update');

		// Faz-6: Taşeron Portal V2 (İSG, Avans, İhale)
		Route::get('worker-documents', [App\Http\Controllers\SubcontractorPortal\WorkerDocumentController::class, 'index'])->name('documents.index');
		Route::get('worker-documents/create', [App\Http\Controllers\SubcontractorPortal\WorkerDocumentController::class, 'create'])->name('documents.create');
		Route::post('worker-documents', [App\Http\Controllers\SubcontractorPortal\WorkerDocumentController::class, 'store'])->name('documents.store');

		Route::get('advance-requests', [App\Http\Controllers\SubcontractorPortal\AdvanceRequestController::class, 'index'])->name('advance_requests.index');
		Route::get('advance-requests/create', [App\Http\Controllers\SubcontractorPortal\AdvanceRequestController::class, 'create'])->name('advance_requests.create');
		Route::post('advance-requests', [App\Http\Controllers\SubcontractorPortal\AdvanceRequestController::class, 'store'])->name('advance_requests.store');

		Route::get('bids', [App\Http\Controllers\SubcontractorPortal\TenderBiddingController::class, 'index'])->name('bids.index');
		Route::get('bids/create', [App\Http\Controllers\SubcontractorPortal\TenderBiddingController::class, 'create'])->name('bids.create');
		Route::post('bids', [App\Http\Controllers\SubcontractorPortal\TenderBiddingController::class, 'store'])->name('bids.store');
	});
});

// Location API routes (public - no auth required)
Route::get('api/locations/provinces', [App\Http\Controllers\Api\LocationController::class, 'getProvinces'])->name('api.locations.provinces');
Route::get('api/locations/districts', [App\Http\Controllers\Api\LocationController::class, 'getDistricts'])->name('api.locations.districts');

// Activities & Notifications API (public but returns limited data if not authenticated)
Route::get('api/activities/recent', [App\Http\Controllers\Api\ActivityController::class, 'recent'])->name('api.activities.recent');

// Radio Stations SQL API Routes
Route::get('api/radio-stations', [App\Http\Controllers\RadioStationController::class, 'index'])->name('api.radio-stations.index');
Route::post('api/radio-stations', [App\Http\Controllers\RadioStationController::class, 'store'])->name('api.radio-stations.store');
Route::delete('api/radio-stations/{id}', [App\Http\Controllers\RadioStationController::class, 'destroy'])->name('api.radio-stations.destroy');

// Notifications require authentication
Route::middleware('auth')->group(function(){
    Route::get('api/notifications/count', [App\Http\Controllers\Api\NotificationController::class, 'count'])->name('api.notifications.count');
	// Notification pages & actions
	Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
	Route::post('notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
	Route::post('notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
});

// Portfolio Management route moved to auth group below
Route::get('portfolio/management', [App\Http\Controllers\PortfolioManagementController::class, 'index'])->name('portfolio.management.index');

// Backwards-compatible aliases for missing route names used by views
Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'root'])->name('dashboard');

// Help section aliases
Route::get('help', [App\Http\Controllers\HelpController::class, 'index'])->name('help.search');
Route::get('help/index', [App\Http\Controllers\HelpController::class, 'index'])->name('help.index');
Route::get('help/articles', [App\Http\Controllers\HelpController::class, 'articles'])->name('help.articles');
Route::get('help/article/{id}', [App\Http\Controllers\HelpController::class, 'article'])->name('help.article');
Route::get('help/category/{slug}', [App\Http\Controllers\HelpController::class, 'category'])->name('help.category');
Route::get('help/faq', [App\Http\Controllers\HelpController::class, 'faq'])->name('help.faq');
Route::get('help/contact', [App\Http\Controllers\HelpController::class, 'contact'])->name('help.contact');
Route::get('help/guide', [App\Http\Controllers\HelpController::class, 'guide'])->name('help.guide');
Route::get('help/videos', [App\Http\Controllers\HelpController::class, 'videos'])->name('help.videos');

// Gallery alias
Route::get('gallery', function(){ return View::exists('gallery.index') ? view('gallery.index') : redirect('/'); })->name('gallery.index');

// Inventory route aliases (simple stubs redirecting to inventory index if exists)
Route::get('inventory/create', function(){ return redirect()->route('inventory.index'); })->name('inventory.create');
Route::get('inventory/{id}', function($id){ return redirect()->route('inventory.index'); })->name('inventory.show');
Route::get('inventory/{id}/edit', function($id){ return redirect()->route('inventory.index'); })->name('inventory.edit');
// Provide a safe fallback for inventory.index used by views
Route::get('inventory', function(){
	// Directly redirect to portfolio management (avoid self-redirect loop)
	return redirect()->route('portfolio.management.index');
})->name('inventory.index');

// Tasks full REST alias (map missing names to TaskController methods)
// Use numeric constraint on {id} to avoid capturing literal segments like `create`.
Route::get('tasks/{id}', [App\Http\Controllers\TaskController::class, 'show'])
	->name('tasks.show')
	->where('id','[0-9]+');

Route::put('tasks/{id}', [App\Http\Controllers\TaskController::class, 'update'])
	->name('tasks.update')
	->where('id','[0-9]+');

Route::delete('tasks/{id}', [App\Http\Controllers\TaskController::class, 'destroy'])
	->name('tasks.destroy')
	->where('id','[0-9]+');

Route::get('tasks/{id}/edit', [App\Http\Controllers\TaskController::class, 'edit'])
	->name('tasks.edit')
	->where('id','[0-9]+');

Route::get('apps-kanban', [App\Http\Controllers\TaskController::class, 'kanban'])->name('apps-kanban');

// Apps contact / register alias fallback
Route::get('register', function(){ return redirect()->route('login.show'); })->name('register');

// Field QHSE permit action aliases
Route::post('field/qhse/safety/permits/{id}/approve', [App\Http\Controllers\IsgController::class, 'permitApprove'])->name('field.qhse.safety.permits.approve');
Route::post('field/qhse/safety/permits/{id}/close', [App\Http\Controllers\IsgController::class, 'permitClose'])->name('field.qhse.safety.permits.close');

// projects.contact alias - forward to apps-contact/profile contact
Route::get('projects/contact', function(){ return redirect()->route('apps.contact'); })->name('projects.contact');

// Backwards-compatible placeholder routes used by topbar links and legacy templates.
// These simply redirect to a sensible existing page or render a minimal view when available.
Route::get('documents', function(){ return redirect()->route('projects.documents.all'); })->name('documents.index');
Route::get('components', function(){ return redirect()->route('portfolio.management.index'); })->name('components.index');
Route::get('forms/wizard', function(){ return redirect()->route('portfolio.management.index'); })->name('forms.wizard');
Route::get('documentation', function(){ return redirect()->route('portfolio.management.index'); })->name('documentation.index');
Route::get('knowledge-base', function(){ return redirect()->route('portfolio.management.index'); })->name('knowledge-base.index');

// Backwards-compatible route name used in some views/links
// Some templates expect the route name `portfolio.management` (without .index).
// Provide a lightweight redirect so legacy calls work without changing many views.
Route::get('portfolio/management/legacy-redirect', function(){
	return redirect()->route('portfolio.management.index');
})->name('portfolio.management');

// Portfolio Financials index (was missing, previously caught by catch-all and redirected to dashboard)
Route::get('portfolio/financials', [App\Http\Controllers\PortfolioFinancialController::class, 'index'])->name('portfolio.financials.index');
Route::get('portfolio/financials/group-items', [App\Http\Controllers\PortfolioFinancialController::class, 'groupItems'])->name('portfolio.financials.group_items');

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

	$months = ['Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül'];

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
	$regions = ['Marmara', 'Ege', 'İç Anadolu', 'Akdeniz', 'Karadeniz', 'Doğu Anadolu', 'Güneydoğu Anadolu'];
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

// Lightweight API endpoint for facility map (nearby assets)
Route::get('api/facility/nearby-assets', [App\Http\Controllers\FacilityController::class, 'nearbyAssets'])->name('api.facility.nearby_assets');

// Simple authentication routes (must be before catch-all)
Route::get('login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login.show');
Route::post('login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Lock Screen Routes
Route::get('lock-screen', [App\Http\Controllers\LockScreenController::class, 'show'])->name('lock-screen.show');
Route::post('lock-screen/lock', [App\Http\Controllers\LockScreenController::class, 'lock'])->name('lock-screen.lock');
Route::post('lock-screen/unlock', [App\Http\Controllers\LockScreenController::class, 'unlock'])->name('lock-screen.unlock');
Route::get('lock-screen/status', [App\Http\Controllers\LockScreenController::class, 'status'])->name('lock-screen.status');

// Backwards-compatible placeholder routes for topbar links that may not have full pages yet
// These prevent 404s from old templates and redirect to sensible existing pages.
// Messages page - uses MessageController (falls back to placeholder if Message model missing)
Route::get('messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
// Message actions
Route::post('messages/{id}/read', [App\Http\Controllers\MessageController::class, 'markAsRead'])->name('messages.read');
Route::post('messages/{id}/delete', [App\Http\Controllers\MessageController::class, 'destroy'])->name('messages.delete');
Route::get('messages/{id}/reply', [App\Http\Controllers\MessageController::class, 'replyForm'])->name('messages.reply.form');
Route::post('messages/{id}/reply', [App\Http\Controllers\MessageController::class, 'storeReply'])->name('messages.reply.store');

// Activities page (uses existing ActivityController)
Route::get('activities', [App\Http\Controllers\ActivityController::class, 'index'])->name('activities.index');
// Backwards-compatible calendar route expected by some templates
Route::get('calendar', function () { return redirect()->route('activities.index'); })->name('calendar.index');

// Tasks page (uses existing TaskController) - require authentication
Route::get('tasks', [App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index')->middleware('auth');
// Provide create route expected by some views/templates (protected)
Route::get('tasks/create', [App\Http\Controllers\TaskController::class, 'create'])->name('tasks.create')->middleware('auth');
Route::post('tasks', [App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store')->middleware('auth');

// Notes CRUD (index + create/edit/delete)
Route::get('notes', [App\Http\Controllers\NoteController::class, 'index'])->name('notes.index');
Route::get('notes/create', [App\Http\Controllers\NoteController::class, 'create'])->name('notes.create');
Route::post('notes', [App\Http\Controllers\NoteController::class, 'store'])->name('notes.store');
Route::get('notes/{id}/edit', [App\Http\Controllers\NoteController::class, 'edit'])->name('notes.edit');
Route::put('notes/{id}', [App\Http\Controllers\NoteController::class, 'update'])->name('notes.update');
Route::post('notes/{id}/delete', [App\Http\Controllers\NoteController::class, 'destroy'])->name('notes.delete');

// Profile / contact page
Route::get('apps-contact', [App\Http\Controllers\ProfileController::class, 'show'])->name('apps.contact');
Route::get('apps-contact/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('apps.contact.edit');
Route::put('apps-contact', [App\Http\Controllers\ProfileController::class, 'update'])->name('apps.contact.update');

// Backwards-compatible route names for profile (legacy templates)
Route::get('profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
Route::get('profile/create', [App\Http\Controllers\ProfileController::class, 'create'])->name('profile.create');
Route::post('profile', [App\Http\Controllers\ProfileController::class, 'store'])->name('profile.store');
Route::get('profile/{id}/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
Route::put('profile/{id}', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
Route::delete('profile/{id}', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

// Explicit portfolio route(s) - listing handled by ProjectController (supports search & pagination)
Route::get('portfolio/all-projects', [App\Http\Controllers\ProjectController::class, 'index'])->name('portfolio.all-projects');
Route::get('portfolio/projects/{id}', [App\Http\Controllers\ProjectController::class, 'show'])->name('portfolio.project.show');
Route::post('portfolio/projects/{id}/like', [App\Http\Controllers\ProjectLikeController::class, 'store'])->name('portfolio.project.like');
// API-style endpoint for project preview (used by modal preview)
Route::get('api/projects/{id}', [App\Http\Controllers\ProjectController::class, 'apiShow']);
Route::get('api/projects/{id}/villas', [App\Http\Controllers\ProjectController::class, 'apiGetVillas']);

// Program yönetimi routes
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
	// Admin - Kullanıcı Yönetimi
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

	// KDV oranları yönetimi (admin)
	Route::get('admin/kdv', [App\Http\Controllers\Admin\KdvRateController::class, 'index'])->name('admin.kdv.index');
	Route::get('admin/kdv/create', [App\Http\Controllers\Admin\KdvRateController::class, 'create'])->name('admin.kdv.create');
	Route::post('admin/kdv', [App\Http\Controllers\Admin\KdvRateController::class, 'store'])->name('admin.kdv.store');
	Route::get('admin/kdv/{kdvRate}/edit', [App\Http\Controllers\Admin\KdvRateController::class, 'edit'])->name('admin.kdv.edit');
	Route::put('admin/kdv/{kdvRate}', [App\Http\Controllers\Admin\KdvRateController::class, 'update'])->name('admin.kdv.update');
	Route::delete('admin/kdv/{kdvRate}', [App\Http\Controllers\Admin\KdvRateController::class, 'destroy'])->name('admin.kdv.destroy');

	Route::post('portfolio/programs/{program}/milestones', [App\Http\Controllers\ProgramController::class, 'storeMilestone'])->name('portfolio.programs.milestones.store');
	Route::put('portfolio/programs/{program}/milestones/{milestone}', [App\Http\Controllers\ProgramController::class, 'updateMilestone'])->name('portfolio.programs.milestones.update');
	Route::delete('portfolio/programs/{program}/milestones/{milestone}', [App\Http\Controllers\ProgramController::class, 'destroyMilestone'])->name('portfolio.programs.milestones.destroy');

	// Subcontractor management (admin) - create/store
	Route::get('admin/subcontractors/create', [App\Http\Controllers\SubcontractorController::class, 'create'])->name('subcontractor.create');
	Route::post('admin/subcontractors', [App\Http\Controllers\SubcontractorController::class, 'store'])->name('subcontractor.store');
	Route::get('admin/subcontractors/{id}/edit', [App\Http\Controllers\SubcontractorController::class, 'edit'])->name('subcontractor.edit');
	Route::put('admin/subcontractors/{id}', [App\Http\Controllers\SubcontractorController::class, 'update'])->name('subcontractor.update');
	Route::delete('admin/subcontractors/{id}', [App\Http\Controllers\SubcontractorController::class, 'destroy'])->name('subcontractor.destroy');

	// Trashed management
	Route::get('admin/subcontractors/trashed', [App\Http\Controllers\SubcontractorController::class, 'trashedIndex'])->name('subcontractor.trashed');
	Route::post('admin/subcontractors/{id}/restore', [App\Http\Controllers\SubcontractorController::class, 'restore'])->name('subcontractor.restore');
	Route::post('admin/subcontractors/{id}/force-delete', [App\Http\Controllers\SubcontractorController::class, 'forceDelete'])->name('subcontractor.force_delete');
});
// Portfolio Management route moved to auth group below
Route::get('portfolio/financials/export', [App\Http\Controllers\PortfolioFinancialController::class, 'exportCsv'])->name('portfolio.financials.export');
Route::get('portfolio/financials/export-xlsx', [App\Http\Controllers\PortfolioFinancialController::class, 'exportXlsx'])->name('portfolio.financials.export_xlsx');
// Bütçe ve Maliyet Kontrolü (Cost & Budget Control)
Route::get('cost/budget', [App\Http\Controllers\CostBudgetController::class, 'index'])->name('cost.budget');
// Hakedişler / Ödemeler - Payments module
Route::get('cost/payments', [App\Http\Controllers\PaymentController::class, 'index'])->name('cost.payments.index');
Route::middleware('auth')->group(function(){
	// Progress Payments (Hakediş Yönetimi)
	Route::get('progress-payments', [App\Http\Controllers\ProgressPaymentController::class, 'index'])->name('progress-payments.index');
	Route::get('progress-payments/create', [App\Http\Controllers\ProgressPaymentController::class, 'create'])->name('progress-payments.create');
	Route::post('progress-payments', [App\Http\Controllers\ProgressPaymentController::class, 'store'])->name('progress-payments.store');
	Route::get('progress-payments/reports', [App\Http\Controllers\ProgressPaymentController::class, 'reports'])->name('progress-payments.reports');
	Route::get('progress-payments/reports/index', [App\Http\Controllers\ProgressPaymentController::class, 'reports'])->name('progress-payments.reports.index');
	Route::get('progress-payments/{progressPayment}', [App\Http\Controllers\ProgressPaymentController::class, 'show'])->name('progress-payments.show');
	Route::get('progress-payments/{progressPayment}/edit', [App\Http\Controllers\ProgressPaymentController::class, 'edit'])->name('progress-payments.edit');
	Route::put('progress-payments/{progressPayment}', [App\Http\Controllers\ProgressPaymentController::class, 'update'])->name('progress-payments.update');
	Route::delete('progress-payments/{progressPayment}', [App\Http\Controllers\ProgressPaymentController::class, 'destroy'])->name('progress-payments.destroy');
	Route::post('progress-payments/{progressPayment}/submit', [App\Http\Controllers\ProgressPaymentController::class, 'submit'])->name('progress-payments.submit');
	Route::post('progress-payments/{progressPayment}/approve', [App\Http\Controllers\ProgressPaymentController::class, 'approve'])->name('progress-payments.approve');
	Route::post('progress-payments/{progressPayment}/reject', [App\Http\Controllers\ProgressPaymentController::class, 'reject'])->name('progress-payments.reject');

	// Currency Exchanges (Döviz Alım/Satım & Kur Çevrim Yönetimi)
	Route::get('currency-exchanges', [App\Http\Controllers\CurrencyExchangeController::class, 'index'])->name('currency_exchanges.index');
	Route::get('currency_exchanges', [App\Http\Controllers\CurrencyExchangeController::class, 'index']);
	Route::get('currency-exchanges/create', [App\Http\Controllers\CurrencyExchangeController::class, 'create'])->name('currency_exchanges.create');
	Route::get('currency_exchanges/create', [App\Http\Controllers\CurrencyExchangeController::class, 'create']);
	Route::post('currency-exchanges', [App\Http\Controllers\CurrencyExchangeController::class, 'store'])->name('currency_exchanges.store');
	Route::get('currency-exchanges/{currencyExchange}', [App\Http\Controllers\CurrencyExchangeController::class, 'show'])->name('currency_exchanges.show');
	Route::get('currency_exchanges/{currencyExchange}', [App\Http\Controllers\CurrencyExchangeController::class, 'show']);
	Route::get('currency-exchanges/{currencyExchange}/edit', [App\Http\Controllers\CurrencyExchangeController::class, 'edit'])->name('currency_exchanges.edit');
	Route::put('currency-exchanges/{currencyExchange}', [App\Http\Controllers\CurrencyExchangeController::class, 'update'])->name('currency_exchanges.update');
	Route::delete('currency-exchanges/{currencyExchange}', [App\Http\Controllers\CurrencyExchangeController::class, 'destroy'])->name('currency_exchanges.destroy');
	Route::get('api/currency-exchanges/rate', [App\Http\Controllers\CurrencyExchangeController::class, 'conversionRate'])->name('currency_exchanges.rate');

	// E-Dönüşüm & E-Fatura (E-Invoice & Digital Transformation)
	Route::get('finance/edoc/efatura', [App\Http\Controllers\EDocController::class, 'efatura'])->name('finance.edoc.efatura');
	Route::get('finance/edoc/create', [App\Http\Controllers\EDocController::class, 'create'])->name('finance.edoc.create');
	Route::get('finance/edoc/{id}', [App\Http\Controllers\EDocController::class, 'show'])->name('finance.edoc.show')->where('id', '[0-9]+');
	Route::get('finance/edoc/{id}/edit', [App\Http\Controllers\EDocController::class, 'edit'])->name('finance.edoc.edit')->where('id', '[0-9]+');


	// Field operations web routes
	Route::get('field/operations', [App\Http\Controllers\FieldOperationsController::class, 'indexWeb'])->name('field.operations');
	Route::get('field/operations/create', [App\Http\Controllers\FieldOperationsController::class, 'createWeb'])->name('field.operations.create');
	Route::get('field/operations/{id}', [App\Http\Controllers\FieldOperationsController::class, 'showWeb'])->name('field.operations.show')->where('id', '[0-9]+');
	Route::get('field/operations/{id}/edit', [App\Http\Controllers\FieldOperationsController::class, 'editWeb'])->name('field.operations.edit')->where('id', '[0-9]+');
	Route::delete('field/operations/{id}', [App\Http\Controllers\FieldOperationsController::class, 'destroyWeb'])->name('field.operations.destroy')->where('id', '[0-9]+');

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

	// İSG (İş Sağlığı ve Güvenliği) Routes
	Route::prefix('field/qhse/safety')->name('field.qhse.safety.')->group(function() {
		Route::get('/', [App\Http\Controllers\IsgController::class, 'dashboard'])->name('dashboard');

		// Olay ve Kaza Raporlama
		Route::get('incidents', [App\Http\Controllers\IsgController::class, 'incidentIndex'])->name('incidents.index');
		Route::get('incidents/create', [App\Http\Controllers\IsgController::class, 'incidentCreate'])->name('incident.create');
		Route::post('incidents', [App\Http\Controllers\IsgController::class, 'incidentStore'])->name('incident.store');
		Route::get('incidents/{incident}', [App\Http\Controllers\IsgController::class, 'incidentShow'])->name('incident.show');

		// İşe Başlama İzinleri (Permit to Work)
		Route::get('permits', [App\Http\Controllers\IsgController::class, 'permitsIndex'])->name('permits.index');
		Route::get('permits/create', [App\Http\Controllers\IsgController::class, 'permitCreate'])->name('permit.create');
		Route::post('permits', [App\Http\Controllers\IsgController::class, 'permitStore'])->name('permit.store');
		Route::get('permits/{permit}', [App\Http\Controllers\IsgController::class, 'permitShow'])->name('permit.show');
		Route::post('permits/{permit}/approve', [App\Http\Controllers\IsgController::class, 'permitApprove'])->name('permit.approve');
		Route::post('permits/{permit}/close', [App\Http\Controllers\IsgController::class, 'permitClose'])->name('permit.close');

		// Saha Güvenlik Eğitimleri (Toolbox Talks)
		Route::get('toolbox', [App\Http\Controllers\IsgController::class, 'toolboxIndex'])->name('toolbox.index');
		Route::get('toolbox/create', [App\Http\Controllers\IsgController::class, 'toolboxCreate'])->name('toolbox.create');
		Route::post('toolbox', [App\Http\Controllers\IsgController::class, 'toolboxStore'])->name('toolbox.store');
	});
	// Template CRUD routes
	Route::get('contracts/library/templates/create', [App\Http\Controllers\ContractLibraryController::class, 'createTemplate'])->name('contracts.library.templates.create');
	Route::post('contracts/library/templates', [App\Http\Controllers\ContractLibraryController::class, 'storeTemplate'])->name('contracts.library.templates.store');
	Route::get('contracts/library/templates/{id}/edit', [App\Http\Controllers\ContractLibraryController::class, 'editTemplate'])->name('contracts.library.templates.edit');
	Route::put('contracts/library/templates/{id}', [App\Http\Controllers\ContractLibraryController::class, 'updateTemplate'])->name('contracts.library.templates.update');
	Route::delete('contracts/library/templates/{id}', [App\Http\Controllers\ContractLibraryController::class, 'destroyTemplate'])->name('contracts.library.templates.destroy');
	Route::post('contracts/library/templates/{id}/duplicate', [App\Http\Controllers\ContractLibraryController::class, 'duplicateTemplate'])->name('contracts.library.templates.duplicate');

	// Clause CRUD routes
	Route::get('contracts/library/clauses/create', [App\Http\Controllers\ContractLibraryController::class, 'createClause'])->name('contracts.library.clauses.create');
	Route::post('contracts/library/clauses', [App\Http\Controllers\ContractLibraryController::class, 'storeClause'])->name('contracts.library.clauses.store');
	Route::get('contracts/library/clauses/{id}/edit', [App\Http\Controllers\ContractLibraryController::class, 'editClause'])->name('contracts.library.clauses.edit');
	Route::put('contracts/library/clauses/{id}', [App\Http\Controllers\ContractLibraryController::class, 'updateClause'])->name('contracts.library.clauses.update');
	Route::delete('contracts/library/clauses/{id}', [App\Http\Controllers\ContractLibraryController::class, 'destroyClause'])->name('contracts.library.clauses.destroy');
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
// İnsan Kaynakları (HR) Routes
Route::middleware('auth')->group(function(){
	Route::prefix('resources/hr')->name('resources.hr.')->group(function() {
		Route::get('/', [App\Http\Controllers\HRController::class, 'dashboard'])->name('index');
		Route::get('dashboard', [App\Http\Controllers\HRController::class, 'dashboard'])->name('dashboard');
		Route::get('workforce-planning', [App\Http\Controllers\HRController::class, 'workforcePlanning'])->name('workforce_planning');
		Route::get('workforce-planning-alt', [App\Http\Controllers\HRController::class, 'workforcePlanning'])->name('workforce-planning');
		Route::get('employees', [App\Http\Controllers\HRController::class, 'employeeList'])->name('employees');
		Route::get('employee-list', [App\Http\Controllers\HRController::class, 'employeeList'])->name('employee_list');
		Route::get('employee-profile/{id}', [App\Http\Controllers\HRController::class, 'employeeProfile'])->name('employee_profile');
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
Route::get('opportunities/kanban', [App\Http\Controllers\OpportunityController::class, 'kanban'])->name('opportunities.kanban');
Route::get('opportunities/list', [App\Http\Controllers\OpportunityController::class, 'list'])->name('opportunities.list');
Route::get('opportunities/create', [App\Http\Controllers\OpportunityController::class, 'create'])->name('opportunities.create');
Route::post('opportunities', [App\Http\Controllers\OpportunityController::class, 'store'])->name('opportunities.store');
Route::get('opportunities/{id}', [App\Http\Controllers\OpportunityController::class, 'show'])->name('opportunities.show');
Route::get('opportunities/{id}/edit', [App\Http\Controllers\OpportunityController::class, 'edit'])->name('opportunities.edit');
Route::put('opportunities/{id}', [App\Http\Controllers\OpportunityController::class, 'update'])->name('opportunities.update');
Route::post('opportunities/{id}/move', [App\Http\Controllers\OpportunityController::class, 'moveStage'])->name('opportunities.move');
Route::post('opportunities/{id}/convert', [App\Http\Controllers\OpportunityController::class, 'convertToProject'])->name('opportunities.convert');
Route::post('opportunities/{id}/activity', [App\Http\Controllers\OpportunityController::class, 'addActivity'])->name('opportunities.activity');
Route::post('opportunities/{id}/note', [App\Http\Controllers\OpportunityController::class, 'addNote'])->name('opportunities.note');
Route::delete('opportunities/{id}', [App\Http\Controllers\OpportunityController::class, 'destroy'])->name('opportunities.destroy');
Route::post('opportunities/bulk-action', [App\Http\Controllers\OpportunityController::class, 'bulkAction'])->name('opportunities.bulk');
Route::get('opportunities/export/csv', [App\Http\Controllers\OpportunityController::class, 'exportCsv'])->name('opportunities.export');

// Sales Targets (Satış Hedefleri)
Route::prefix('sales-targets')->name('sales-targets.')->group(function() {
    Route::get('/', [App\Http\Controllers\SalesTargetController::class, 'index'])->name('index');
    Route::get('dashboard', [App\Http\Controllers\SalesTargetController::class, 'dashboard'])->name('dashboard');
    Route::get('create', [App\Http\Controllers\SalesTargetController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\SalesTargetController::class, 'store'])->name('store');
    Route::get('{salesTarget}', [App\Http\Controllers\SalesTargetController::class, 'show'])->name('show');
    Route::get('{salesTarget}/edit', [App\Http\Controllers\SalesTargetController::class, 'edit'])->name('edit');
    Route::put('{salesTarget}', [App\Http\Controllers\SalesTargetController::class, 'update'])->name('update');
    Route::delete('{salesTarget}', [App\Http\Controllers\SalesTargetController::class, 'destroy'])->name('destroy');
});

// KVKK Consents (Onay Yönetimi)
Route::prefix('kvkk-consents')->name('kvkk-consents.')->group(function() {
    Route::get('/', [App\Http\Controllers\KvkkConsentController::class, 'index'])->name('index');
    Route::get('create', [App\Http\Controllers\KvkkConsentController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\KvkkConsentController::class, 'store'])->name('store');
    Route::get('{kvkkConsent}', [App\Http\Controllers\KvkkConsentController::class, 'show'])->name('show');
    Route::get('{kvkkConsent}/edit', [App\Http\Controllers\KvkkConsentController::class, 'edit'])->name('edit');
    Route::put('{kvkkConsent}', [App\Http\Controllers\KvkkConsentController::class, 'update'])->name('update');
    Route::delete('{kvkkConsent}', [App\Http\Controllers\KvkkConsentController::class, 'destroy'])->name('destroy');
    Route::post('{kvkkConsent}/verify-sms', [App\Http\Controllers\KvkkConsentController::class, 'verifySms'])->name('verify-sms');
    Route::post('{kvkkConsent}/send-email', [App\Http\Controllers\KvkkConsentController::class, 'sendEmailVerification'])->name('send-email');
    Route::post('{kvkkConsent}/reject', [App\Http\Controllers\KvkkConsentController::class, 'reject'])->name('reject');
});

// Options (Opsiyon Yönetimi)
Route::prefix('options')->name('options.')->group(function() {
    Route::get('/', [App\Http\Controllers\OptionController::class, 'index'])->name('index');
    Route::get('about-to-expire', [App\Http\Controllers\OptionController::class, 'aboutToExpire'])->name('about-to-expire');
    Route::get('create', [App\Http\Controllers\OptionController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\OptionController::class, 'store'])->name('store');
    Route::get('{option}', [App\Http\Controllers\OptionController::class, 'show'])->name('show');
    Route::get('{option}/edit', [App\Http\Controllers\OptionController::class, 'edit'])->name('edit');
    Route::put('{option}', [App\Http\Controllers\OptionController::class, 'update'])->name('update');
    Route::post('{option}/convert-to-project', [App\Http\Controllers\OptionController::class, 'convertToProject'])->name('convert-to-project');
    Route::post('{option}/cancel', [App\Http\Controllers\OptionController::class, 'cancel'])->name('cancel');
    Route::delete('{option}', [App\Http\Controllers\OptionController::class, 'destroy'])->name('destroy');
});

// Feasibility & Capital Planning
Route::get('portfolio/tenders/feasibility-capital', [App\Http\Controllers\FeasibilityController::class, 'index'])->name('feasibility.index');
Route::get('portfolio/tenders/feasibility-capital/compare', [App\Http\Controllers\FeasibilityController::class, 'compare'])->name('feasibility.compare');
Route::post('portfolio/tenders/feasibility-capital/compare', [App\Http\Controllers\FeasibilityController::class, 'compare'])->name('feasibility.compare.post');
Route::get('portfolio/tenders/feasibility-capital/create', [App\Http\Controllers\FeasibilityController::class, 'create'])->name('feasibility.create');
Route::post('portfolio/tenders/feasibility-capital', [App\Http\Controllers\FeasibilityController::class, 'store'])->name('feasibility.store');
// Specific static feasibility routes (MUST be registered before {id} to avoid 404 matching)
Route::get('portfolio/tenders/feasibility-capital', [App\Http\Controllers\FeasibilityController::class, 'index'])->name('feasibility.index');
Route::get('portfolio/tenders/feasibility-capital/create', [App\Http\Controllers\FeasibilityController::class, 'create'])->name('feasibility.create');
Route::post('portfolio/tenders/feasibility-capital', [App\Http\Controllers\FeasibilityController::class, 'store'])->name('feasibility.store');
Route::get('portfolio/tenders/feasibility-capital/trash', [App\Http\Controllers\FeasibilityController::class, 'trash'])->name('feasibility.trash');
Route::get('portfolio/tenders/feasibility-capital/compare', [App\Http\Controllers\FeasibilityController::class, 'compare'])->name('feasibility.compare.get');
Route::post('portfolio/tenders/feasibility-capital/compare', [App\Http\Controllers\FeasibilityController::class, 'compare'])->name('feasibility.compare');

// Parameterized feasibility routes with numeric constraint
Route::get('portfolio/tenders/feasibility-capital/{id}/edit', [App\Http\Controllers\FeasibilityController::class, 'edit'])->where('id', '[0-9]+')->name('feasibility.edit');
Route::put('portfolio/tenders/feasibility-capital/{id}', [App\Http\Controllers\FeasibilityController::class, 'update'])->where('id', '[0-9]+')->name('feasibility.update');
Route::delete('portfolio/tenders/feasibility-capital/{id}', [App\Http\Controllers\FeasibilityController::class, 'destroy'])->where('id', '[0-9]+')->name('feasibility.destroy');
Route::get('portfolio/tenders/feasibility-capital/{id}', [App\Http\Controllers\FeasibilityController::class, 'show'])->where('id', '[0-9]+')->name('feasibility.show');

// Action endpoints with numeric constraint
Route::post('portfolio/tenders/feasibility-capital/{id}/restore', [App\Http\Controllers\FeasibilityController::class, 'restore'])->where('id', '[0-9]+')->name('feasibility.restore');
Route::post('portfolio/tenders/feasibility-capital/{id}/force-delete', [App\Http\Controllers\FeasibilityController::class, 'forceDelete'])->where('id', '[0-9]+')->name('feasibility.force_delete');
Route::post('portfolio/tenders/feasibility-capital/{id}/undo', [App\Http\Controllers\FeasibilityController::class, 'restore'])->where('id', '[0-9]+')->name('feasibility.undo');
Route::get('portfolio/tenders/feasibility-capital/{id}/export-pdf', [App\Http\Controllers\FeasibilityController::class, 'exportPdf'])->where('id', '[0-9]+')->name('feasibility.export_pdf');
Route::post('portfolio/tenders/feasibility-capital/{id}/duplicate', [App\Http\Controllers\FeasibilityController::class, 'duplicate'])->where('id', '[0-9]+')->name('feasibility.duplicate');
Route::post('portfolio/tenders/feasibility-capital/{id}/recalc', [App\Http\Controllers\FeasibilityController::class, 'recalc'])->where('id', '[0-9]+')->name('feasibility.recalc');
// Development helper: seed demo feasibility studies (only in local environment)
if (app()->environment('local')) {
	Route::get('dev/seed-feasibility', function() {
		// run the seed script in a separate PHP process to avoid including bootstrap twice
		$script = base_path('scripts/seed_feasibility_demo.php');
		if (!file_exists($script)) {
			return redirect()->route('feasibility.index')->with('error','Seed script bulunamadı');
		}

		$php = defined('PHP_BINARY') ? PHP_BINARY : 'php';
		$cmd = $php . ' ' . escapeshellarg($script) . ' 2>&1';
		try {
			$output = @shell_exec($cmd);
		} catch (\Exception $e) {
			return redirect()->route('feasibility.index')->with('error','Seed çalıştırılamadı: '.$e->getMessage());
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
		return redirect()->route('feasibility.index')->with('error','Seed tamamlandı (beklenmeyen çıktı), kontrol edin.')->with('dev_seed_output', $short);
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

// Tender package preparation (İhale Paketi Hazırla)
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
Route::resource('projects', App\Http\Controllers\ProjectController::class)->except(['index']);

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

// Developer helper: lightweight "login as" route for automated testing in local/debug environments only.
if (config('app.debug')) {
	Route::get('__dev/login-as/{id}', function ($id) {
		try {
			\Illuminate\Support\Facades\Auth::loginUsingId((int) $id);
		} catch (\Throwable $e) {
			return response('dev login failed: ' . $e->getMessage(), 500);
		}
		return redirect('/');
	})->name('dev.login-as');

	// Create a lightweight test user and return its ID/email. This is only for local debug.
	Route::get('__dev/create-test-user', function () {
		try {
			// Idempotent: if the user already exists, return it instead of creating duplicate
			$email = 'dev.tester+1@example.local';
			$existing = \App\Models\User::where('email', $email)->first();
			if ($existing) {
				return response()->json(['id' => $existing->id, 'email' => $existing->email]);
			}

			$u = new \App\Models\User();
			$u->name = 'Dev Tester';
			$u->email = $email;
			$u->password = bcrypt('password');
			// mark verified and non-admin by default
			if (Schema::hasColumn('users', 'email_verified_at')) $u->email_verified_at = now();
			$u->save();
			return response()->json(['id' => $u->id, 'email' => $u->email]);
		} catch (\Throwable $e) {
			return response('create-test-user failed: ' . $e->getMessage(), 500);
		}
	})->name('dev.create-test-user');
}

// Finance & Accounting placeholder routes (added for sidebar links)
Route::get('finance/dashboard', [App\Http\Controllers\FinanceController::class, 'dashboard'])->name('finance.dashboard');

// Ensure supplier accounts route exists for legacy references while serving the finance view.
Route::get('finance/accounts/suppliers', [App\Http\Controllers\FinanceController::class, 'suppliers'])->name('finance.accounts.suppliers');

// Accounts
Route::get('finance/accounts/customers', [App\Http\Controllers\FinanceController::class, 'customers'])->name('finance.accounts.customers');
// Recycle bin for customers (must be before parameterized routes)
Route::get('finance/accounts/customers/recycle', [App\Http\Controllers\FinanceController::class, 'customersRecycle'])->name('finance.accounts.customers.recycle');
Route::post('finance/accounts/customers/{id}/restore', [App\Http\Controllers\FinanceController::class, 'customerRestore'])->name('finance.accounts.customers.restore')->where('id','[0-9]+');
// Customer CRUD (create/edit/delete)
Route::get('finance/accounts/customers/create', [App\Http\Controllers\FinanceController::class, 'customerCreate'])->name('finance.accounts.customers.create');
Route::post('finance/accounts/customers', [App\Http\Controllers\FinanceController::class, 'customerStore'])->name('finance.accounts.customers.store');
Route::get('finance/accounts/customers/{id}/edit', [App\Http\Controllers\FinanceController::class, 'customerEdit'])->name('finance.accounts.customers.edit')->where('id','[0-9]+');
Route::put('finance/accounts/customers/{id}', [App\Http\Controllers\FinanceController::class, 'customerUpdate'])->name('finance.accounts.customers.update')->where('id','[0-9]+');
Route::delete('finance/accounts/customers/{id}', [App\Http\Controllers\FinanceController::class, 'customerDestroy'])->name('finance.accounts.customers.destroy')->where('id','[0-9]+');
Route::get('finance/accounts/customers/{id}', [App\Http\Controllers\FinanceController::class, 'customerShow'])->name('finance.accounts.customers.show')->where('id', '[0-9]+');
Route::get('customers/{id}', [App\Http\Controllers\FinanceController::class, 'customerShow'])->name('customers.show')->where('id', '[0-9]+');
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
// Expense category management (AJAX)
Route::post('finance/expenses/category/add', [App\Http\Controllers\FinanceController::class, 'addExpenseCategory'])->name('finance.expenses.category.add');
Route::post('finance/expenses/category/edit', [App\Http\Controllers\FinanceController::class, 'editExpenseCategory'])->name('finance.expenses.category.edit');
Route::post('finance/expenses/category/delete', [App\Http\Controllers\FinanceController::class, 'deleteExpenseCategory'])->name('finance.expenses.category.delete');

// Separate categories management page
Route::get('finance/expenses/categories', [App\Http\Controllers\FinanceController::class, 'categoriesManager'])->name('finance.expenses.categories');
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
// Yeni çek kaydetme endpointi (AJAX) - yetki kontrolü ekleniyor
Route::post('finance/cheques/store', [App\Http\Controllers\FinanceController::class, 'chequesStore'])->middleware('auth')->name('finance.cheques.store');
// Çek silme/iptal (AJAX) - yetki kontrolü
Route::delete('finance/cheques/{id}', [App\Http\Controllers\FinanceController::class, 'chequesDestroy'])->middleware('auth')->name('finance.cheques.destroy');
// Tekil çek göster (AJAX)
Route::get('finance/cheques/{id}', [App\Http\Controllers\FinanceController::class, 'chequesShow'])->name('finance.cheques.show');
// Güncelle (AJAX, auth)
Route::put('finance/cheques/{id}', [App\Http\Controllers\FinanceController::class, 'chequesUpdate'])->middleware('auth')->name('finance.cheques.update');
// Assign cheque to supplier
Route::post('finance/cheques/{id}/assign', [App\Http\Controllers\FinanceController::class, 'chequesAssign'])->middleware('auth')->name('finance.cheques.assign');

// Stock
Route::get('finance/stock/cards', [App\Http\Controllers\FinanceController::class, 'stockCards'])->name('finance.stock.cards');
Route::post('finance/stock/cards', [App\Http\Controllers\FinanceController::class, 'storeStockCard'])->name('finance.stock.cards.store');
Route::get('finance/stock/cards/{id}', [App\Http\Controllers\FinanceController::class, 'showStockCard'])->name('finance.stock.cards.show');
Route::put('finance/stock/cards/{id}', [App\Http\Controllers\FinanceController::class, 'updateStockCard'])->name('finance.stock.cards.update');
Route::delete('finance/stock/cards/{id}', [App\Http\Controllers\FinanceController::class, 'destroyStockCard'])->name('finance.stock.cards.destroy');

// Select2 stock search for invoice item selection (protected)
Route::get('finance/stock/search', [App\Http\Controllers\FinanceController::class, 'searchStock'])
	->name('finance.stock.search')
	->middleware(['auth','throttle:60,1']);
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

// Simple invoice create/store for testing Select2 stock selection
Route::get('finance/invoices/create', [App\Http\Controllers\FinanceController::class, 'invoicesCreate'])->name('finance.invoices.create');
Route::post('finance/invoices', [App\Http\Controllers\FinanceController::class, 'invoicesStore'])->name('finance.invoices.store');

// Invoice index (list) route
Route::get('finance/invoices', [App\Http\Controllers\FinanceController::class, 'invoicesIndex'])->name('finance.invoices.index');

// Invoice action routes (show, edit, print, email, payment, destroy)
Route::get('finance/invoices/{id}', [App\Http\Controllers\FinanceController::class, 'invoicesShow'])->name('finance.invoices.show');
Route::get('finance/invoices/{id}/edit', [App\Http\Controllers\FinanceController::class, 'invoicesEdit'])->name('finance.invoices.edit');
Route::put('finance/invoices/{id}', [App\Http\Controllers\FinanceController::class, 'invoicesUpdate'])->name('finance.invoices.update');
Route::get('finance/invoices/{id}/print', [App\Http\Controllers\FinanceController::class, 'invoicesPrint'])->name('finance.invoices.print');
Route::get('finance/invoices/{id}/email', [App\Http\Controllers\FinanceController::class, 'invoicesEmailForm'])->name('finance.invoices.email.form');
Route::post('finance/invoices/{id}/email', [App\Http\Controllers\FinanceController::class, 'invoicesEmailSend'])->name('finance.invoices.email.send');
Route::get('finance/invoices/{id}/payment', [App\Http\Controllers\FinanceController::class, 'invoicesPaymentForm'])->name('finance.invoices.payment.form');
Route::post('finance/invoices/{id}/payment', [App\Http\Controllers\FinanceController::class, 'invoicesPaymentStore'])->name('finance.invoices.payment.store');
Route::delete('finance/invoices/{id}', [App\Http\Controllers\FinanceController::class, 'invoicesDestroy'])->name('finance.invoices.destroy');

// HR / Payroll
Route::get('finance/hr/expenses', [App\Http\Controllers\FinanceController::class, 'hrExpenses'])->name('finance.hr.expenses');
Route::get('finance/hr/expenses/create', [App\Http\Controllers\FinanceController::class, 'createHrExpense'])->name('finance.hr.expenses.create');
Route::get('finance/hr/expenses/export', [App\Http\Controllers\FinanceController::class, 'exportHrExpenses'])->name('finance.hr.expenses.export');
Route::get('finance/hr/expenses/{id}', [App\Http\Controllers\FinanceController::class, 'showHrExpense'])->name('finance.hr.expenses.show')->where('id', '[0-9]+');
Route::get('finance/hr/expenses/{id}/edit', [App\Http\Controllers\FinanceController::class, 'editHrExpense'])->name('finance.hr.expenses.edit')->where('id', '[0-9]+');
Route::post('finance/hr/expenses/{id}/approve', [App\Http\Controllers\FinanceController::class, 'approveHrExpense'])->name('finance.hr.expenses.approve');
Route::post('finance/hr/expenses/{id}/reject', [App\Http\Controllers\FinanceController::class, 'rejectHrExpense'])->name('finance.hr.expenses.reject');
Route::get('finance/hr/advances', [App\Http\Controllers\FinanceController::class, 'hrAdvances'])->name('finance.hr.advances');
Route::get('finance/hr/advances/create', [App\Http\Controllers\FinanceController::class, 'createAdvanceForm'])->name('finance.hr.advances.create_form');
Route::get('finance/hr/advances/{id}', [App\Http\Controllers\FinanceController::class, 'showAdvance'])->name('finance.hr.advances.show')->where('id', '[0-9]+');
Route::get('finance/hr/advances/{id}/edit', [App\Http\Controllers\FinanceController::class, 'editAdvance'])->name('finance.hr.advances.edit')->where('id', '[0-9]+');
Route::post('finance/hr/advances/create', [App\Http\Controllers\FinanceController::class, 'createAdvance'])->name('finance.hr.advances.create');
Route::post('finance/hr/advances/{id}/approve', [App\Http\Controllers\FinanceController::class, 'approveAdvance'])->name('finance.hr.advances.approve');
Route::post('finance/hr/advances/{id}/reject', [App\Http\Controllers\FinanceController::class, 'rejectAdvance'])->name('finance.hr.advances.reject');
Route::post('finance/hr/advances/{id}/payment', [App\Http\Controllers\FinanceController::class, 'addAdvancePayment'])->name('finance.hr.advances.payment');
Route::get('finance/hr/advances/{id}/details', [App\Http\Controllers\FinanceController::class, 'getAdvanceDetails'])->name('finance.hr.advances.details');
Route::get('finance/hr/payroll', [App\Http\Controllers\FinanceController::class, 'hrPayroll'])->name('finance.hr.payroll');
Route::get('finance/hr/payroll/create', [App\Http\Controllers\FinanceController::class, 'createPayrollForm'])->name('finance.hr.payroll.create_form');
Route::get('finance/hr/payroll/{id}', [App\Http\Controllers\FinanceController::class, 'showPayroll'])->name('finance.hr.payroll.show')->where('id', '[0-9]+');
Route::get('finance/hr/payroll/{id}/edit', [App\Http\Controllers\FinanceController::class, 'editPayroll'])->name('finance.hr.payroll.edit')->where('id', '[0-9]+');
Route::post('finance/hr/payroll/{id}/approve', [App\Http\Controllers\FinanceController::class, 'approvePayroll'])->name('finance.hr.payroll.approve');
Route::post('finance/hr/payroll/bulk-action', [App\Http\Controllers\FinanceController::class, 'bulkPayrollAction'])->name('finance.hr.payroll.bulk');
Route::get('finance/hr/payroll/{id}/details', [App\Http\Controllers\FinanceController::class, 'getPayrollDetails'])->name('finance.hr.payroll.details');
Route::post('finance/hr/payroll/{id}/update', [App\Http\Controllers\FinanceController::class, 'updatePayroll'])->name('finance.hr.payroll.update');

// Purchasing Quotations (Satın Alma Teklif Toplama & Karşılaştırma Portalı)
Route::get('purchasing/quotations', [App\Http\Controllers\PurchasingController::class, 'quotations'])->name('purchasing.quotations.index');
Route::get('purchasing/quotations/create', [App\Http\Controllers\PurchasingController::class, 'createQuotation'])->name('purchasing.quotations.create');
Route::get('purchasing/quotations/{id}', [App\Http\Controllers\PurchasingController::class, 'showQuotation'])->name('purchasing.quotations.show')->where('id', '[0-9]+');
Route::get('purchasing/quotations/{id}/edit', [App\Http\Controllers\PurchasingController::class, 'editQuotation'])->name('purchasing.quotations.edit')->where('id', '[0-9]+');
Route::post('purchasing/quotations/{id}/approve', [App\Http\Controllers\PurchasingController::class, 'approveQuotation'])->name('purchasing.quotations.approve');
Route::post('purchasing/quotations/{id}/reject', [App\Http\Controllers\PurchasingController::class, 'rejectQuotation'])->name('purchasing.quotations.reject');

// Purchasing Approvals (Satın Alma Onay Bekleyen Talepler & Onay Akışı)
Route::get('purchasing/approvals/pending', [App\Http\Controllers\PurchasingController::class, 'pendingApprovals'])->name('purchasing.approvals.pending');
Route::get('purchasing/approvals/create', [App\Http\Controllers\PurchasingController::class, 'createApprovalForm'])->name('purchasing.approvals.create');
Route::get('purchasing/approvals/{id}', [App\Http\Controllers\PurchasingController::class, 'showApproval'])->name('purchasing.approvals.show')->where('id', '[0-9]+');
Route::get('purchasing/approvals/{id}/edit', [App\Http\Controllers\PurchasingController::class, 'editApproval'])->name('purchasing.approvals.edit')->where('id', '[0-9]+');
Route::post('purchasing/approvals/{id}/process', [App\Http\Controllers\PurchasingController::class, 'processApproval'])->name('purchasing.approvals.process');

// Stock Cards (Stok Kartları & Şantiye Envanter Portalı)
Route::get('finance/stock/cards', [App\Http\Controllers\FinanceController::class, 'stockCards'])->name('finance.stock.cards');
Route::get('finance/stock/cards/create', [App\Http\Controllers\FinanceController::class, 'createStockCard'])->name('finance.stock.cards.create');
Route::get('finance/stock/cards/{id}', [App\Http\Controllers\FinanceController::class, 'showStockCard'])->name('finance.stock.cards.show')->where('id', '[0-9]+');
Route::get('finance/stock/cards/{id}/edit', [App\Http\Controllers\FinanceController::class, 'editStockCard'])->name('finance.stock.cards.edit')->where('id', '[0-9]+');

// Stock Movements (Stok Hareketleri & Ambar Fişleri)
Route::get('finance/stock/movements', [App\Http\Controllers\FinanceController::class, 'stockMovements'])->name('finance.stock.movements');
Route::get('finance/stock/movements/create', [App\Http\Controllers\FinanceController::class, 'createStockMovement'])->name('finance.stock.movements.create');
Route::get('finance/stock/movements/{id}', [App\Http\Controllers\FinanceController::class, 'showStockMovement'])->name('finance.stock.movements.show')->where('id', '[0-9]+');
Route::get('finance/stock/movements/{id}/edit', [App\Http\Controllers\FinanceController::class, 'editStockMovement'])->name('finance.stock.movements.edit')->where('id', '[0-9]+');

// Equipment Management (İş Makinesi & Ekipman Parkı Portalı)
Route::get('resources/equipment', [App\Http\Controllers\EquipmentController::class, 'index'])->name('resources.equipment.index');
Route::get('resources/equipment/create', [App\Http\Controllers\EquipmentController::class, 'create'])->name('resources.equipment.create');
Route::post('resources/equipment', [App\Http\Controllers\EquipmentController::class, 'store'])->name('resources.equipment.store');
Route::get('resources/equipment/{equipment}', [App\Http\Controllers\EquipmentController::class, 'show'])->name('resources.equipment.show');
Route::get('resources/equipment/{equipment}/edit', [App\Http\Controllers\EquipmentController::class, 'edit'])->name('resources.equipment.edit');
Route::put('resources/equipment/{equipment}', [App\Http\Controllers\EquipmentController::class, 'update'])->name('resources.equipment.update');
Route::delete('resources/equipment/{equipment}', [App\Http\Controllers\EquipmentController::class, 'destroy'])->name('resources.equipment.destroy');

// Financial Assets & Fixed Assets (Finans - Demirbaş & Sabit Kıymet Envanter Portalı)
Route::get('finance/assets/list', [App\Http\Controllers\FinanceController::class, 'assetsList'])->name('finance.assets.list');
Route::get('finance/assets/create', [App\Http\Controllers\FinanceController::class, 'createAsset'])->name('finance.assets.create');
Route::get('finance/assets/{id}', [App\Http\Controllers\FinanceController::class, 'showAsset'])->name('finance.assets.show')->where('id', '[0-9]+');
Route::get('finance/assets/{id}/edit', [App\Http\Controllers\FinanceController::class, 'editAsset'])->name('finance.assets.edit')->where('id', '[0-9]+');

// Expenses
Route::get('finance/expenses/general', [App\Http\Controllers\FinanceController::class, 'expensesGeneral'])->name('finance.expenses.general');
Route::post('finance/expenses/store', [App\Http\Controllers\FinanceController::class, 'storeExpense'])->name('finance.expenses.store');
Route::get('finance/expenses/edit/{id}', [App\Http\Controllers\FinanceController::class, 'editExpense'])->name('finance.expenses.edit');
Route::get('finance/expenses/view/{id}', [App\Http\Controllers\FinanceController::class, 'viewExpense'])->name('finance.expenses.view');
Route::post('finance/expenses/update/{id}', [App\Http\Controllers\FinanceController::class, 'updateExpense'])->name('finance.expenses.post_update');
Route::post('finance/expenses/approve/{id}', [App\Http\Controllers\FinanceController::class, 'approveExpense'])->name('finance.expenses.post_approve');
Route::post('finance/expenses/reject/{id}', [App\Http\Controllers\FinanceController::class, 'rejectExpense'])->name('finance.expenses.post_reject');
Route::post('finance/expenses/delete/{id}', [App\Http\Controllers\FinanceController::class, 'deleteExpense'])->name('finance.expenses.post_delete');
Route::post('finance/expenses/bulk-approve', [App\Http\Controllers\FinanceController::class, 'expensesBulkApprove'])->name('finance.expenses.bulk_approve');
Route::get('finance/expenses/reports', [App\Http\Controllers\FinanceController::class, 'expenseReports'])->name('finance.expenses.reports');

// Named routes for expense actions (show, edit, destroy, approve, reject, create, export)
Route::get('finance/expenses/create', [App\Http\Controllers\FinanceController::class, 'expensesCreate'])->name('finance.expenses.create');
// Bu rota tekrar olduğu için kaldırıldı veya yorumlandı
// Route::post('finance/expenses', [App\Http\Controllers\FinanceController::class, 'expensesStore'])->name('finance.expenses.store');
Route::get('finance/expenses/{id}', [App\Http\Controllers\FinanceController::class, 'expensesShow'])->name('finance.expenses.show')->where('id', '[0-9]+');
// Bu rota tekrar olduğu için kaldırıldı veya yorumlandı
// Route::get('finance/expenses/{id}/edit', [App\Http\Controllers\FinanceController::class, 'expensesEdit'])->name('finance.expenses.edit')->where('id', '[0-9]+');
Route::put('finance/expenses/{id}', [App\Http\Controllers\FinanceController::class, 'expensesUpdate'])->name('finance.expenses.update')->where('id', '[0-9]+');
Route::delete('finance/expenses/{id}', [App\Http\Controllers\FinanceController::class, 'expensesDestroy'])->name('finance.expenses.destroy')->where('id', '[0-9]+');
Route::post('finance/expenses/{id}/approve', [App\Http\Controllers\FinanceController::class, 'expensesApprove'])->name('finance.expenses.approve')->where('id', '[0-9]+');
Route::post('finance/expenses/{id}/reject', [App\Http\Controllers\FinanceController::class, 'expensesReject'])->name('finance.expenses.reject')->where('id', '[0-9]+');
// Undo (geri al) for approve/reject/delete
Route::post('finance/expenses/{id}/undo', [App\Http\Controllers\FinanceController::class, 'expensesUndo'])->name('finance.expenses.undo')->where('id', '[0-9]+');
Route::get('finance/expenses/export', [App\Http\Controllers\FinanceController::class, 'expensesExport'])->name('finance.expenses.export');
Route::get('finance/expenses/reports', [App\Http\Controllers\FinanceController::class, 'expensesReports'])->name('finance.expenses.reports');
Route::post('finance/expenses/bulk-approve', [App\Http\Controllers\FinanceController::class, 'expensesBulkApprove'])->name('finance.expenses.bulk_approve');

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
// Contracts management (Sözleşme Yönetimi)
Route::get('contracts/management', [App\Http\Controllers\ContractsController::class, 'management'])->name('contracts.management');
// Basic create/store routes for contracts (used by management UI) - require auth
Route::middleware('auth')->group(function(){
	Route::get('contracts/create', [App\Http\Controllers\ContractsController::class, 'create'])->name('contracts.create');
	Route::post('contracts', [App\Http\Controllers\ContractsController::class, 'store'])->name('contracts.store');
	Route::get('contracts/{contract}', [App\Http\Controllers\ContractsController::class, 'show'])->name('contracts.show');
	Route::get('contracts/{contract}/edit', [App\Http\Controllers\ContractsController::class, 'edit'])->name('contracts.edit');
	Route::put('contracts/{contract}', [App\Http\Controllers\ContractsController::class, 'update'])->name('contracts.update');
	Route::delete('contracts/{contract}', [App\Http\Controllers\ContractsController::class, 'destroy'])->name('contracts.destroy');
});
// Subcontractor management (Taşeron Yönetimi)
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
	Route::post('subcontractor/{id}/quote', [App\Http\Controllers\SubcontractorController::class, 'quoteStore'])->name('subcontractor.quote.store');
	Route::post('subcontractor/{id}/documents/upload', [App\Http\Controllers\SubcontractorController::class, 'uploadDocument'])->name('subcontractor.documents.upload');
	Route::get('subcontractor/companies/export/csv', [App\Http\Controllers\SubcontractorController::class, 'exportCompaniesCsv'])->name('subcontractor.companies.export');
});

// Main project module routes - all require authentication

Route::middleware('auth')->group(function() {
	// Project document control
	Route::get('projects/documents', [App\Http\Controllers\DocumentController::class, 'all'])->name('projects.documents.all');

	// Project planning and timeline module
	Route::get('projects/planning', [App\Http\Controllers\ProjectPlanningController::class, 'index'])->name('projects.planning');
	Route::get('projects/{project}/planning/tasks', [App\Http\Controllers\ProjectPlanningController::class, 'tasks'])->name('projects.planning.tasks');
	Route::get('projects/{project}/planning/statistics', [App\Http\Controllers\ProjectPlanningController::class, 'getProjectStatistics'])->name('projects.planning.statistics');
	Route::get('projects/{project}/planning/export', [App\Http\Controllers\ProjectPlanningController::class, 'exportTasks'])->name('projects.planning.export');
	Route::get('projects/{project}/planning/risk-analysis', [App\Http\Controllers\ProjectPlanningController::class, 'riskAnalysis'])->name('projects.planning.risk_analysis');
	Route::get('projects/{project}/planning/resource-report', [App\Http\Controllers\ProjectPlanningController::class, 'resourceReport'])->name('projects.planning.resource_report');

// Communication module (tekrar eden rota kaldırıldı)
});

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

	// Communication module (detailed routes, require auth)
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

	// Project Cost Analysis
	Route::get('projects/{project}/costs', [App\Http\Controllers\ProjectCostController::class, 'index'])->name('projects.costs.index');
	Route::get('projects/{project}/costs/create', [App\Http\Controllers\ProjectCostController::class, 'create'])->name('projects.costs.create');
	Route::post('projects/{project}/costs', [App\Http\Controllers\ProjectCostController::class, 'store'])->name('projects.costs.store');

	// Villa Management
	Route::get('projects/{project}/villas/{villa}/edit', [App\Http\Controllers\VillaController::class, 'edit'])->name('projects.villas.edit');
	Route::put('projects/{project}/villas/{villa}', [App\Http\Controllers\VillaController::class, 'update'])->name('projects.villas.update');

	// Signed download for communication attachments (rfi/submittal/meeting/action)
	Route::get('projects/{project}/communication/{type}/{id}/download/{file}', [App\Http\Controllers\CommunicationController::class, 'downloadAttachment'])->name('projects.communication.attachment.download')->middleware('signed');

	// helper to get a temporary signed url (returns JSON) - no signed middleware because this endpoint creates the signed url
	Route::get('projects/{project}/communication/{type}/{id}/signed-url/{file}', [App\Http\Controllers\CommunicationController::class, 'signedAttachmentUrl'])->name('projects.communication.attachment.signed');

	// Equipment Management (Ekipman Parkı)
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

// BIM / Model Yönetimi
// Public signed file delivery for viewers (keeps file protected but allows temporary access)
Route::get('bim/models/{id}/file/public', [App\Http\Controllers\BimModelController::class, 'filePublic'])->name('bim.models.file.public')->middleware('signed');

Route::middleware('auth')->group(function(){
	Route::get('bim/models', [App\Http\Controllers\BimModelController::class, 'index'])->name('bim.models.index');
	Route::get('bim/models/upload', [App\Http\Controllers\BimModelController::class, 'uploadForm'])->name('bim.models.upload');
	// Partial endpoint for AJAX panel injection (returns only the inner form)
	Route::get('bim/models/upload-partial', [App\Http\Controllers\BimModelController::class, 'uploadPartial'])->name('bim.models.upload.partial');
	Route::post('bim/models', [App\Http\Controllers\BimModelController::class, 'store'])->name('bim.models.store');
	Route::get('bim/models/{id}/viewer', [App\Http\Controllers\BimModelController::class, 'viewer'])->name('bim.models.viewer');
	Route::get('bim/models/compare/{id1}/{id2}', [App\Http\Controllers\BimModelController::class, 'compare'])->name('bim.models.compare');
	Route::get('bim/models/{id}/file', [App\Http\Controllers\BimModelController::class, 'file'])->name('bim.models.file');
	Route::post('bim/models/{id}/convert-rvt', [App\Http\Controllers\BimModelController::class, 'convertRvt'])->name('bim.models.convert_rvt');
	Route::post('bim/clash-tests', [App\Http\Controllers\BimModelController::class, 'createClashTest'])->name('bim.clashtests.create');
	Route::get('bim/clash-tests/{id}', [App\Http\Controllers\BimModelController::class, 'clashDetail'])->name('bim.clashtests.detail');

	// BIM Simülasyonları (4D / 5D)
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
	Route::get('projects/{project}/documents', [App\Http\Controllers\DocumentController::class, 'projectDocuments'])->name('projects.documents.index');
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
	Route::get('supply/vendors/create', [App\Http\Controllers\SupplyController::class, 'create'])->name('supply.vendors.create');
	Route::get('supply/vendors/{vendor}', [App\Http\Controllers\SupplyController::class, 'show'])->name('supply.vendors.show');
	Route::post('supply/vendors', [App\Http\Controllers\SupplyController::class, 'store'])->name('supply.vendors.store');
	Route::post('supply/vendors/{vendor}/reviews', [App\Http\Controllers\SupplyController::class, 'storeReview'])->name('supply.vendors.reviews.store');
	Route::post('supply/vendors/{vendor}/documents', [App\Http\Controllers\SupplyController::class, 'uploadDocument'])->name('supply.vendors.documents.upload');
	// Simple JSON list for AJAX filtering
	Route::get('api/supply/vendors', [App\Http\Controllers\SupplyController::class, 'apiList'])->name('api.supply.vendors');
	Route::get('api/supply/vendors/stats', [App\Http\Controllers\SupplyController::class, 'apiStats'])->name('api.supply.vendors.stats');

		// Mirror routes under Finance (finance.accounts.suppliers.*)
		Route::get('finance/accounts/suppliers', [App\Http\Controllers\FinanceController::class, 'suppliers'])->name('finance.accounts.suppliers');
		Route::get('finance/accounts/suppliers/create', [App\Http\Controllers\SupplyController::class, 'create'])->name('finance.accounts.suppliers.create');
		// Recycle bin must be defined before the {vendor} parameter route to avoid route-capture conflicts
		Route::get('finance/accounts/suppliers/recycle', [App\Http\Controllers\SupplyController::class, 'recycle'])->name('finance.accounts.suppliers.recycle');
		Route::get('finance/accounts/suppliers/{vendor}', [App\Http\Controllers\SupplyController::class, 'show'])->name('finance.accounts.suppliers.show');
		// Edit / Update / Destroy for suppliers
		Route::get('finance/accounts/suppliers/{vendor}/edit', [App\Http\Controllers\SupplyController::class, 'edit'])->name('finance.accounts.suppliers.edit');
		Route::put('finance/accounts/suppliers/{vendor}', [App\Http\Controllers\SupplyController::class, 'update'])->name('finance.accounts.suppliers.update');
		Route::delete('finance/accounts/suppliers/{vendor}', [App\Http\Controllers\SupplyController::class, 'destroy'])->name('finance.accounts.suppliers.destroy');
		// Recycle bin & restore
		Route::get('finance/accounts/suppliers/recycle', [App\Http\Controllers\SupplyController::class, 'recycle'])->name('finance.accounts.suppliers.recycle');
		Route::post('finance/accounts/suppliers/{vendor}/restore', [App\Http\Controllers\SupplyController::class, 'restore'])->name('finance.accounts.suppliers.restore');
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
	// CRUD endpoints for assets (AJAX)
	Route::post('facility/assets', [App\Http\Controllers\FacilityController::class, 'storeAsset'])->name('facility.assets.store');
	Route::put('facility/assets/{asset}', [App\Http\Controllers\FacilityController::class, 'updateAsset'])->name('facility.assets.update');
	Route::delete('facility/assets/{asset}', [App\Http\Controllers\FacilityController::class, 'destroyAsset'])->name('facility.assets.destroy');
	Route::post('facility/assets/{asset}/documents', [App\Http\Controllers\FacilityController::class, 'uploadDocument'])->name('facility.assets.upload-document');
	// Create maintenance request from asset detail
	Route::post('facility/assets/{asset}/maintenances', [App\Http\Controllers\FacilityController::class, 'storeMaintenance'])->name('facility.assets.maintenances.store');
	Route::get('facility/maintenance', [App\Http\Controllers\FacilityController::class, 'maintenance'])->name('facility.maintenance');
	Route::get('facility/requests', [App\Http\Controllers\FacilityController::class, 'requests'])->name('facility.requests');
	Route::post('facility/requests', [App\Http\Controllers\FacilityController::class, 'storeRequest'])->name('facility.requests.store');
	Route::get('facility/reports', [App\Http\Controllers\FacilityController::class, 'reports'])->name('facility.reports');

	// QR Code Management - Teknik Destek
	Route::prefix('facility/qr-codes')->name('facility.qr.')->group(function() {
		Route::get('/', [App\Http\Controllers\FacilityController::class, 'qrCodesList'])->name('index');
		Route::get('create', [App\Http\Controllers\FacilityController::class, 'createQrCode'])->name('create');
		Route::post('store', [App\Http\Controllers\FacilityController::class, 'storeQrCode'])->name('store');
		Route::get('{id}', [App\Http\Controllers\FacilityController::class, 'showQrCode'])->name('show');
		Route::get('{id}/download', [App\Http\Controllers\FacilityController::class, 'downloadQrCode'])->name('download');
		Route::post('{id}/disable', [App\Http\Controllers\FacilityController::class, 'disableQrCode'])->name('disable');
		Route::post('{id}/enable', [App\Http\Controllers\FacilityController::class, 'enableQrCode'])->name('enable');
		Route::get('project/{projectId}/generate-all', [App\Http\Controllers\FacilityController::class, 'generateProjectQrCodes'])->name('generate-all');
	});

	// SLA Management - Teknik Destek
	Route::prefix('facility/sla')->name('facility.sla.')->group(function() {
		Route::get('/', [App\Http\Controllers\FacilityController::class, 'slaConfig'])->name('index');
		Route::get('create', [App\Http\Controllers\FacilityController::class, 'createSlaConfig'])->name('create');
		Route::post('store', [App\Http\Controllers\FacilityController::class, 'storeSlaConfig'])->name('store');
		Route::get('{id}/edit', [App\Http\Controllers\FacilityController::class, 'editSlaConfig'])->name('edit');
		Route::put('{id}', [App\Http\Controllers\FacilityController::class, 'updateSlaConfig'])->name('update');
		Route::delete('{id}', [App\Http\Controllers\FacilityController::class, 'deleteSlaConfig'])->name('destroy');
		Route::get('dashboard/{projectId}', [App\Http\Controllers\FacilityController::class, 'slaDashboard'])->name('dashboard');
		Route::get('alerts/{projectId}', [App\Http\Controllers\FacilityController::class, 'slaAlerts'])->name('alerts');
		Route::post('alerts/{alertId}/acknowledge', [App\Http\Controllers\FacilityController::class, 'acknowledgeSlaAlert'])->name('acknowledge');
	});
});

// Analytics & Reports routes (auth olmadan erişilebilir)
Route::get('analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
Route::get('analytics/risk-map', [App\Http\Controllers\AnalyticsController::class, 'riskMap'])->name('analytics.risk-map');
Route::get('analytics/benchmarking', [App\Http\Controllers\AnalyticsController::class, 'benchmarking'])->name('analytics.benchmarking');
Route::get('analytics/profit-forecast', [App\Http\Controllers\AnalyticsController::class, 'profitForecast'])->name('analytics.profit-forecast');
Route::get('analytics/what-if', [App\Http\Controllers\AnalyticsController::class, 'whatIf'])->name('analytics.what-if');
Route::get('analytics/wizard', [App\Http\Controllers\AnalyticsController::class, 'wizard'])->name('analytics.wizard');
Route::post('analytics/wizard/generate', [App\Http\Controllers\AnalyticsController::class, 'generateReport'])->name('analytics.wizard.generate');
Route::post('analytics/wizard/save', [App\Http\Controllers\AnalyticsController::class, 'saveReport'])->name('analytics.wizard.save');

// Analytics & Reports routes (auth ile)
Route::middleware('auth')->group(function(){
	// Development helper: public access to what-if UI for local testing (no auth)
	if (app()->environment('local') || in_array(config('app.debug'), [true, 'true'])) {
		Route::get('dev/analytics/what-if', function() {
			// Prepare same data structure as AnalyticsController@whatIf but bypass auth/middleware
			$controller = app()->make(App\Http\Controllers\AnalyticsController::class);
			$baselineData = $controller->buildBaselineData();
			$activeProjects = App\Models\Project::whereIn('status', ['active','planning'])->get(['id','name']);
			return view('analytics.what_if', compact('baselineData', 'activeProjects'));
		})->name('dev.analytics.what-if');
	}
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


// System Management Routes (admin)
Route::get('admin/system', [App\Http\Controllers\Admin\SystemController::class, 'index'])->name('admin.system.index');
Route::middleware(['auth'])->group(function () {
	Route::get('admin/license', [App\Http\Controllers\Admin\LicenseController::class, 'index'])->name('admin.license.index');
	Route::get('admin/license/modules', [App\Http\Controllers\Admin\LicenseController::class, 'modules'])->name('admin.license.modules');
	Route::get('admin/license/upgrade', [App\Http\Controllers\Admin\LicenseController::class, 'upgrade'])->name('admin.license.upgrade');
	Route::post('admin/license/activate', [App\Http\Controllers\Admin\LicenseController::class, 'activate'])->name('admin.license.activate');
	Route::post('admin/license/refresh', [App\Http\Controllers\Admin\LicenseController::class, 'refresh'])->name('admin.license.refresh');
	Route::post('admin/license/clear', [App\Http\Controllers\Admin\LicenseController::class, 'clear'])->name('admin.license.clear');

	// PERGEL Modül & Eklenti Yönetimi
	Route::get('admin/modules', [App\Http\Controllers\Admin\ModuleController::class, 'index'])->name('admin.modules.index');
	Route::post('admin/modules/upload', [App\Http\Controllers\Admin\ModuleController::class, 'upload'])->name('admin.modules.upload');
	Route::delete('admin/modules/{slug}', [App\Http\Controllers\Admin\ModuleController::class, 'destroy'])->name('admin.modules.destroy');
	Route::post('admin/system/clear-cache', [App\Http\Controllers\Admin\SystemController::class, 'clearCache'])->name('admin.system.clear-cache');
	Route::post('admin/system/optimize', [App\Http\Controllers\Admin\SystemController::class, 'optimizeSystem'])->name('admin.system.optimize');
	Route::post('admin/system/maintenance', [App\Http\Controllers\Admin\SystemController::class, 'runMaintenance'])->name('admin.system.maintenance');
	Route::post('admin/system/exit-maintenance', [App\Http\Controllers\Admin\SystemController::class, 'exitMaintenance'])->name('admin.system.exit-maintenance');
});

// Standards Management Routes (admin)
Route::middleware(['auth', 'admin'])->group(function () {
	Route::get('admin/standards', [App\Http\Controllers\Admin\StandardsController::class, 'index'])->name('admin.standards.index');
	Route::get('admin/standards/create', [App\Http\Controllers\Admin\StandardsController::class, 'create'])->name('admin.standards.create');
	Route::post('admin/standards', [App\Http\Controllers\Admin\StandardsController::class, 'store'])->name('admin.standards.store');
	Route::get('admin/standards/{standard}', [App\Http\Controllers\Admin\StandardsController::class, 'show'])->name('admin.standards.show');
	Route::get('admin/standards/{standard}/edit', [App\Http\Controllers\Admin\StandardsController::class, 'edit'])->name('admin.standards.edit');
	Route::put('admin/standards/{standard}', [App\Http\Controllers\Admin\StandardsController::class, 'update'])->name('admin.standards.update');
	Route::delete('admin/standards/{standard}', [App\Http\Controllers\Admin\StandardsController::class, 'destroy'])->name('admin.standards.destroy');
	Route::post('admin/standards/{standard}/toggle-status', [App\Http\Controllers\Admin\StandardsController::class, 'toggleStatus'])->name('admin.standards.toggle-status');
	Route::post('admin/standards/bulk-action', [App\Http\Controllers\Admin\StandardsController::class, 'bulkAction'])->name('admin.standards.bulk-action');
	Route::get('admin/standards/export/csv', [App\Http\Controllers\Admin\StandardsController::class, 'exportCsv'])->name('admin.standards.export');
});

// Proje Çöp Kutusu ve Soft Delete işlemleri
Route::get('portfolio/trashed-projects', [App\Http\Controllers\ProjectController::class, 'trashed'])->name('portfolio.trashed-projects');
Route::post('portfolio/projects/{id}/trash', [App\Http\Controllers\ProjectController::class, 'trash'])->name('portfolio.project.trash');
Route::post('portfolio/projects/{id}/restore', [App\Http\Controllers\ProjectController::class, 'restore'])->name('portfolio.project.restore');
Route::delete('portfolio/projects/{id}/force-delete', [App\Http\Controllers\ProjectController::class, 'forceDelete'])->name('portfolio.project.force-delete');


// ŞantiyeConnect Tema Demo Route
Route::get('/santiye-theme-demo', function () {
	return view('santiye-theme-demo');
})->name('santiye.theme.demo');











// Açık kalan tüm blokları güvenli şekilde kapat
// Eksik kapanış parantezlerini ekle (güvenli blok sonu)
// Eğer yukarıda açık kalan bir group/fonksiyon bloğu varsa, burada kapatılır

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



// Ortaklar modülü route'ları
require_once base_path('routes/web-partners.php');

// ============ SATINALMA MODÜLÜ ROTALLARI ============

// Teklifler (Quotations)
Route::prefix('purchasing')->name('purchasing.')->group(function() {
    Route::prefix('quotations')->name('quotations.')->group(function() {
        Route::get('/', [App\Http\Controllers\QuotationController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\QuotationController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\QuotationController::class, 'store'])->name('store');
        Route::get('{quotation}', [App\Http\Controllers\QuotationController::class, 'show'])->name('show');
        Route::post('{quotation}/approve', [App\Http\Controllers\QuotationController::class, 'approve'])->name('approve');
        Route::post('{quotation}/reject', [App\Http\Controllers\QuotationController::class, 'reject'])->name('reject');
    });

    // Onay Süreci (Approvals)
    Route::prefix('approvals')->name('approvals.')->group(function() {
        Route::get('/', [App\Http\Controllers\ApprovalController::class, 'index'])->name('index');
        Route::get('pending', [App\Http\Controllers\ApprovalController::class, 'pending'])->name('pending');
        Route::get('dashboard', [App\Http\Controllers\ApprovalController::class, 'dashboard'])->name('dashboard');
        Route::get('{approval}', [App\Http\Controllers\ApprovalController::class, 'show'])->name('show');
        Route::post('{approval}/approve', [App\Http\Controllers\ApprovalController::class, 'approve'])->name('approve');
        Route::post('{approval}/reject', [App\Http\Controllers\ApprovalController::class, 'reject'])->name('reject');
    });

    // Bütçe Yönetimi (Budgets)
    Route::prefix('budgets')->name('budgets.')->group(function() {
        Route::get('/', [App\Http\Controllers\BudgetController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\BudgetController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\BudgetController::class, 'store'])->name('store');
        Route::get('{budget}/edit', [App\Http\Controllers\BudgetController::class, 'edit'])->name('edit');
        Route::put('{budget}', [App\Http\Controllers\BudgetController::class, 'update'])->name('update');
        Route::get('project/{projectId}', [App\Http\Controllers\BudgetController::class, 'projectBudget'])->name('project');
    });

    // Satın Alma Taleplerini Extend
    Route::prefix('requisitions')->name('requisitions.')->group(function() {
        Route::get('/', function() { return redirect()->route('purchasing.requisitions.index'); });
    });
});

// ============ ADVANCED FINANCE MODULES (FORECAST, RISK, TRENDS, MULTI-CURRENCY) ============

Route::middleware('auth')->group(function() {
    // CASH FLOW FORECAST ROUTES
    Route::prefix('finance/forecast')->name('finance.forecast.')->group(function() {
        Route::get('/', [App\Http\Controllers\CashFlowForecastController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\CashFlowForecastController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\CashFlowForecastController::class, 'store'])->name('store');
        Route::get('dashboard', [App\Http\Controllers\CashFlowForecastController::class, 'dashboard'])->name('dashboard');
        Route::get('comparison', [App\Http\Controllers\CashFlowForecastController::class, 'comparison'])->name('comparison');
        Route::post('project/{projectId}/generate', [App\Http\Controllers\CashFlowForecastController::class, 'generate'])->name('generate');
        Route::get('{forecast}', [App\Http\Controllers\CashFlowForecastController::class, 'show'])->name('show');
        Route::get('{forecast}/edit', [App\Http\Controllers\CashFlowForecastController::class, 'edit'])->name('edit');
        Route::put('{forecast}', [App\Http\Controllers\CashFlowForecastController::class, 'update'])->name('update');
        Route::delete('{forecast}', [App\Http\Controllers\CashFlowForecastController::class, 'destroy'])->name('destroy');
    });

    // FINANCIAL RISK ANALYSIS ROUTES
    Route::prefix('finance/risks')->name('finance.risks.')->group(function() {
        Route::get('/', [App\Http\Controllers\FinancialRiskController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\FinancialRiskController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\FinancialRiskController::class, 'store'])->name('store');
        Route::get('dashboard', [App\Http\Controllers\FinancialRiskController::class, 'dashboard'])->name('dashboard');
        Route::get('{risk}', [App\Http\Controllers\FinancialRiskController::class, 'show'])->name('show');
        Route::get('{risk}/edit', [App\Http\Controllers\FinancialRiskController::class, 'edit'])->name('edit');
        Route::put('{risk}', [App\Http\Controllers\FinancialRiskController::class, 'update'])->name('update');
        Route::delete('{risk}', [App\Http\Controllers\FinancialRiskController::class, 'destroy'])->name('destroy');
        Route::post('project/{projectId}/analyze', [App\Http\Controllers\FinancialRiskController::class, 'analyze'])->name('analyze');
        Route::get('project/{projectId}/critical', [App\Http\Controllers\FinancialRiskController::class, 'getCriticalRisks'])->name('critical');
    });

    // FINANCIAL TREND ANALYSIS ROUTES
    Route::prefix('finance/trends')->name('finance.trends.')->group(function() {
        Route::get('/', [App\Http\Controllers\FinancialTrendController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\FinancialTrendController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\FinancialTrendController::class, 'store'])->name('store');
        Route::get('dashboard', [App\Http\Controllers\FinancialTrendController::class, 'dashboard'])->name('dashboard');
        Route::get('charts', [App\Http\Controllers\FinancialTrendController::class, 'charts'])->name('charts');
        Route::get('comparison', [App\Http\Controllers\FinancialTrendController::class, 'comparison'])->name('comparison');
        Route::get('report', [App\Http\Controllers\FinancialTrendController::class, 'report'])->name('report');
        Route::get('health-score', [App\Http\Controllers\FinancialTrendController::class, 'healthScore'])->name('health-score');
        Route::get('analyze', [App\Http\Controllers\FinancialTrendController::class, 'analyze'])->name('analyze');
        Route::get('export', [App\Http\Controllers\FinancialTrendController::class, 'export'])->name('export');
        Route::get('{trend}', [App\Http\Controllers\FinancialTrendController::class, 'show'])->name('show');
        Route::get('{trend}/edit', [App\Http\Controllers\FinancialTrendController::class, 'edit'])->name('edit');
        Route::put('{trend}', [App\Http\Controllers\FinancialTrendController::class, 'update'])->name('update');
        Route::delete('{trend}', [App\Http\Controllers\FinancialTrendController::class, 'destroy'])->name('destroy');
    });

    // CURRENCY RATE MANAGEMENT ROUTES
    Route::prefix('finance/currency')->name('finance.currency.')->group(function() {
        Route::get('/', [App\Http\Controllers\CurrencyRateController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\CurrencyRateController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\CurrencyRateController::class, 'store'])->name('store');
        Route::get('{rate}', [App\Http\Controllers\CurrencyRateController::class, 'show'])->name('show');
        Route::get('{rate}/edit', [App\Http\Controllers\CurrencyRateController::class, 'edit'])->name('edit');
        Route::put('{rate}', [App\Http\Controllers\CurrencyRateController::class, 'update'])->name('update');
        Route::delete('{rate}', [App\Http\Controllers\CurrencyRateController::class, 'destroy'])->name('destroy');
        Route::post('bulk-import', [App\Http\Controllers\CurrencyRateController::class, 'bulkImport'])->name('bulk-import');
    });

    // CURRENCY API ROUTES
    Route::prefix('api/currency')->name('api.currency.')->group(function() {
        Route::get('rate/{from}/{to}', [App\Http\Controllers\CurrencyRateController::class, 'getRate'])->name('rate');
        Route::get('convert', [App\Http\Controllers\CurrencyRateController::class, 'convert'])->name('convert');
    });

    // CUSTOMER MEETING ROUTES
    Route::resource('customer_meetings', App\Http\Controllers\CustomerMeetingController::class);
    Route::get('customers/{customer}/meetings', [App\Http\Controllers\CustomerMeetingController::class, 'byCustomer'])->name('customer.meetings');

    // PROPERTY DOCUMENT ROUTES (Tapu & Mesken Takibi)
    Route::resource('property_documents', App\Http\Controllers\PropertyDocumentController::class);

    // SALES RESERVATION ROUTES (Satış Opsiyonları)
    Route::resource('sales-reservations', App\Http\Controllers\SalesReservationController::class);
    Route::get('customers/{customer}/sales-reservations', [App\Http\Controllers\SalesReservationController::class, 'byCustomer'])->name('sales-reservations.by_customer');

    // UNIT MANAGEMENT ROUTES (Birim/Daire Yönetimi)
    Route::resource('units', App\Http\Controllers\UnitController::class);
    Route::get('projects/{project}/units', [App\Http\Controllers\UnitController::class, 'listByProject'])->name('units.list_by_project');
    Route::get('units/{unit}/pricing', [App\Http\Controllers\UnitController::class, 'pricing'])->name('units.pricing');

    // PAYMENT PLAN ROUTES (Ödeme Planları)
    Route::resource('payment-plans', App\Http\Controllers\PaymentPlanController::class);
    Route::get('invoices/{invoice}/payment-plans', [App\Http\Controllers\PaymentPlanController::class, 'byInvoice'])->name('payment-plans.by_invoice');

    // CONSULTANT CHANGE ROUTES (Danışman Değişikliği Takibi)
    Route::resource('consultant-changes', App\Http\Controllers\ConsultantChangeController::class);
    Route::get('customers/{customer}/consultant-changes', [App\Http\Controllers\ConsultantChangeController::class, 'byCustomer'])->name('consultant-changes.by_customer');
    Route::get('consultants/{consultant}/changes', [App\Http\Controllers\ConsultantChangeController::class, 'byConsultant'])->name('consultant-changes.by_consultant');

    // INSTALLMENT & PAYMENT TRACKING ROUTES (Taksit Takibi & Ödeme Planlaması)
    Route::resource('installments', App\Http\Controllers\InstallmentController::class);
    Route::get('invoices/{invoice}/installments', [App\Http\Controllers\InstallmentController::class, 'byInvoice'])->name('installments.by_invoice');
    Route::post('installments/{installment}/mark-as-paid', [App\Http\Controllers\InstallmentController::class, 'markAsPaid'])->name('installments.mark_as_paid');
    Route::get('installments/payment-status/report', [App\Http\Controllers\InstallmentController::class, 'paymentStatusReport'])->name('installments.payment_status_report');

    // SALES PERFORMANCE ROUTES
    Route::prefix('sales-performance')->name('sales_performance.')->group(function() {
        Route::get('/', [App\Http\Controllers\SalesPerformanceController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\SalesPerformanceController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\SalesPerformanceController::class, 'store'])->name('store');
        Route::get('users/{user}', [App\Http\Controllers\SalesPerformanceController::class, 'detail'])->name('detail');
        Route::get('edit/{id}', [App\Http\Controllers\SalesPerformanceController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [App\Http\Controllers\SalesPerformanceController::class, 'update'])->name('update');
        Route::delete('destroy/{id}', [App\Http\Controllers\SalesPerformanceController::class, 'destroy'])->name('destroy');
    });

    // SALES INSTALLMENT REPORT ROUTES
    Route::prefix('reports/sales')->name('reports.sales.')->group(function() {
        Route::get('monthly-installment', [App\Http\Controllers\SalesInstallmentReportController::class, 'monthlyReport'])->name('monthly_installment');
        Route::get('invoice/{invoice}/installments', [App\Http\Controllers\SalesInstallmentReportController::class, 'byInvoice'])->name('by_invoice');
        Route::get('payment-timeline', [App\Http\Controllers\SalesInstallmentReportController::class, 'paymentTimeline'])->name('payment_timeline');
        Route::get('balance-detail', [App\Http\Controllers\SalesInstallmentReportController::class, 'balanceDetail'])->name('balance_detail');
        Route::get('accounting-summary', [App\Http\Controllers\SalesInstallmentReportController::class, 'accountingSummary'])->name('accounting_summary');
        Route::get('accounting-summary/export', [App\Http\Controllers\SalesInstallmentReportController::class, 'exportAccountingSummary'])->name('accounting_summary_export');
    });

    // PROJECT FINANCIAL ANALYSIS REPORT ROUTES
    Route::prefix('reports/projects')->name('reports.projects.')->group(function() {
        Route::get('financial-analysis', [App\Http\Controllers\ProjectAnalysisReportController::class, 'financialAnalysis'])->name('financial_analysis');
        Route::get('stock-sales-balance', [App\Http\Controllers\ProjectAnalysisReportController::class, 'stockSalesBalance'])->name('stock_sales_balance');
        Route::get('revenue-analysis', [App\Http\Controllers\ProjectAnalysisReportController::class, 'revenueAnalysis'])->name('revenue_analysis');
        Route::get('discount-profit', [App\Http\Controllers\ProjectAnalysisReportController::class, 'discountAndProfit'])->name('discount_profit');
        Route::get('cost-comparison', [App\Http\Controllers\ProjectAnalysisReportController::class, 'costComparison'])->name('cost_comparison');
    });

    // GENERAL EXECUTIVE KPI & REPORT ROUTES
    Route::prefix('reports/general')->name('reports.general.')->group(function() {
        Route::get('kpi-dashboard', [App\Http\Controllers\GeneralReportsController::class, 'kpiDashboard'])->name('kpi_dashboard');
        Route::get('performance-graphs', [App\Http\Controllers\GeneralReportsController::class, 'performanceGraphs'])->name('performance_graphs');
        Route::get('sales-analysis', [App\Http\Controllers\GeneralReportsController::class, 'salesAnalysis'])->name('sales_analysis');
        Route::get('dynamic-query', [App\Http\Controllers\GeneralReportsController::class, 'dynamicQuery'])->name('dynamic_query');
        Route::get('available-portfolio', [App\Http\Controllers\GeneralReportsController::class, 'availablePortfolio'])->name('available_portfolio');
    });

    // ADVANCED REPORTS ROUTES
    Route::prefix('advanced-reports')->name('advanced_reports.')->group(function() {
        Route::get('/', [App\Http\Controllers\AdvancedReportController::class, 'index'])->name('index');
        Route::get('consultant-performance', [App\Http\Controllers\AdvancedReportController::class, 'consultantPerformance'])->name('consultant_performance');
        Route::get('seasonal-analysis', [App\Http\Controllers\AdvancedReportController::class, 'seasonalAnalysis'])->name('seasonal_analysis');
        Route::get('payment-analysis', [App\Http\Controllers\AdvancedReportController::class, 'paymentAnalysis'])->name('payment_analysis');
        Route::get('activity-dashboard', [App\Http\Controllers\AdvancedReportController::class, 'activityDashboard'])->name('activity_dashboard');
        Route::get('multi-dimensional', [App\Http\Controllers\AdvancedReportController::class, 'multiDimensionalReport'])->name('multi_dimensional');
        Route::get('export', [App\Http\Controllers\AdvancedReportController::class, 'exportReport'])->name('export');
    });

    // ADMIN USER MANAGEMENT ROUTES
    Route::prefix('admin/users')->name('admin.users.')->group(function() {
        Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('store');
        Route::get('export-csv', [App\Http\Controllers\Admin\UserController::class, 'exportCsv'])->name('export_csv');
        Route::post('bulk-action', [App\Http\Controllers\Admin\UserController::class, 'bulkAction'])->name('bulk_action');
        Route::get('{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('show');
        Route::get('{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
        Route::put('{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
        Route::delete('{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('destroy');
        Route::post('{user}/toggle-admin', [App\Http\Controllers\Admin\UserController::class, 'toggleAdmin'])->name('toggle_admin');
        Route::post('{user}/toggle-verification', [App\Http\Controllers\Admin\UserController::class, 'toggleVerification'])->name('toggle_verification');
    });

    // ADMIN LICENSE MANAGEMENT ROUTES
    Route::prefix('admin/license')->name('admin.license.')->group(function() {
        Route::get('/', [App\Http\Controllers\Admin\LicenseController::class, 'index'])->name('index');
        Route::get('modules', [App\Http\Controllers\Admin\LicenseController::class, 'modules'])->name('modules');
        Route::get('upgrade', [App\Http\Controllers\Admin\LicenseController::class, 'upgrade'])->name('upgrade');
        Route::post('activate', [App\Http\Controllers\Admin\LicenseController::class, 'activate'])->name('activate');
        Route::post('refresh', [App\Http\Controllers\Admin\LicenseController::class, 'refresh'])->name('refresh');
        Route::post('clear', [App\Http\Controllers\Admin\LicenseController::class, 'clear'])->name('clear');
    });

    // ADMIN QUALITY STANDARDS & DOCUMENTATION ROUTES
    Route::prefix('admin/standards')->name('admin.standards.')->group(function() {
        Route::get('/', [App\Http\Controllers\Admin\StandardsController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\Admin\StandardsController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\Admin\StandardsController::class, 'store'])->name('store');
        Route::get('export-csv', [App\Http\Controllers\Admin\StandardsController::class, 'exportCsv'])->name('export_csv');
        Route::post('bulk-action', [App\Http\Controllers\Admin\StandardsController::class, 'bulkAction'])->name('bulk_action');
        Route::get('{standard}', [App\Http\Controllers\Admin\StandardsController::class, 'show'])->name('show');
        Route::get('{standard}/edit', [App\Http\Controllers\Admin\StandardsController::class, 'edit'])->name('edit');
        Route::put('{standard}', [App\Http\Controllers\Admin\StandardsController::class, 'update'])->name('update');
        Route::delete('{standard}', [App\Http\Controllers\Admin\StandardsController::class, 'destroy'])->name('destroy');
        Route::post('{standard}/toggle-status', [App\Http\Controllers\Admin\StandardsController::class, 'toggleStatus'])->name('toggle_status');
    });

    // DATA IMPORT & EXPORT MANAGEMENT ROUTES
    Route::prefix('data-import-export')->name('data-import-export.')->group(function() {
        Route::get('/', [App\Http\Controllers\DataImportExportController::class, 'index'])->name('index');
        Route::get('import', [App\Http\Controllers\DataImportExportController::class, 'importForm'])->name('import_form');
        Route::post('import', [App\Http\Controllers\DataImportExportController::class, 'import'])->name('import');
        Route::post('preview', [App\Http\Controllers\DataImportExportController::class, 'preview'])->name('preview');
        Route::get('export', [App\Http\Controllers\DataImportExportController::class, 'exportForm'])->name('export_form');
        Route::post('export', [App\Http\Controllers\DataImportExportController::class, 'export'])->name('export');
        Route::get('logs', [App\Http\Controllers\DataImportExportController::class, 'logs'])->name('logs');
        Route::get('{import}', [App\Http\Controllers\DataImportExportController::class, 'show'])->name('show');
        Route::get('{import}/status', [App\Http\Controllers\DataImportExportController::class, 'importStatus'])->name('status');
        Route::get('{import}/download-errors', [App\Http\Controllers\DataImportExportController::class, 'downloadErrorReport'])->name('download_errors');
        Route::delete('{import}', [App\Http\Controllers\DataImportExportController::class, 'destroy'])->name('destroy');
    });

    // SYSTEM AUDIT & ACTIVITY LOG ROUTES
    Route::prefix('activity-logs')->name('activity-logs.')->group(function() {
        Route::get('/', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('index');
        Route::get('user-audit-trail', [App\Http\Controllers\ActivityLogController::class, 'userAuditTrail'])->name('user_audit_trail');
        Route::get('user-audit', [App\Http\Controllers\ActivityLogController::class, 'userAuditTrail'])->name('user_audit');
        Route::get('model-audit-trail', [App\Http\Controllers\ActivityLogController::class, 'modelAuditTrail'])->name('model_audit_trail');
        Route::get('model-audit', [App\Http\Controllers\ActivityLogController::class, 'modelAuditTrail'])->name('model_audit');
        Route::get('compliance-report', [App\Http\Controllers\ActivityLogController::class, 'complianceReport'])->name('compliance_report');
        Route::get('{activityLog}', [App\Http\Controllers\ActivityLogController::class, 'show'])->name('show');
    });

    // SYSTEM MONITORING & HEALTH DASHBOARD ROUTES
    Route::prefix('admin/system')->name('admin.system.')->group(function() {
        Route::get('/', [App\Http\Controllers\Admin\SystemController::class, 'index'])->name('index');
    });

    // HELP CENTER & USER KNOWLEDGE BASE ROUTES
    Route::prefix('help')->name('help.')->group(function() {
        Route::get('/', [App\Http\Controllers\HelpController::class, 'index'])->name('index');
        Route::get('index', [App\Http\Controllers\HelpController::class, 'index']);
        Route::get('guide', [App\Http\Controllers\HelpController::class, 'guide'])->name('guide');
        Route::get('videos', [App\Http\Controllers\HelpController::class, 'videos'])->name('videos');
        Route::get('faq', [App\Http\Controllers\HelpController::class, 'faq'])->name('faq');
        Route::get('support', [App\Http\Controllers\HelpController::class, 'support'])->name('support');
        Route::post('support', [App\Http\Controllers\HelpController::class, 'storeSupport'])->name('support.store');
        Route::post('sync-remote', [App\Http\Controllers\HelpController::class, 'syncRemote'])->name('sync_remote');
        Route::get('search', [App\Http\Controllers\HelpController::class, 'search'])->name('search');
        Route::get('articles', [App\Http\Controllers\HelpController::class, 'articles'])->name('articles');
        Route::get('article/{id}', [App\Http\Controllers\HelpController::class, 'article'])->name('article');
        Route::get('category/{slug}', [App\Http\Controllers\HelpController::class, 'category'])->name('category');
    });

    // PHASE 3: SALES TARGETS ROUTES
    Route::resource('sales-targets', App\Http\Controllers\SalesTargetController::class);

    // PHASE 3: SALES OPTIONS ROUTES
    Route::resource('sales-options', App\Http\Controllers\SalesOptionController::class);

    // PHASE 3: FLEXIBLE PAYMENT PLANS ROUTES
    Route::prefix('flexible-payments')->name('flexible-payments.')->group(function() {
        Route::get('/', [App\Http\Controllers\FlexiblePaymentController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\FlexiblePaymentController::class, 'create'])->name('create');
        Route::get('scenario-builder', [App\Http\Controllers\FlexiblePaymentController::class, 'scenarioBuilder'])->name('scenario_builder');
        Route::post('calculate-scenario', [App\Http\Controllers\FlexiblePaymentController::class, 'calculateScenario'])->name('calculate_scenario');
        Route::post('/', [App\Http\Controllers\FlexiblePaymentController::class, 'store'])->name('store');
        Route::get('{paymentPlan}', [App\Http\Controllers\FlexiblePaymentController::class, 'show'])->name('show');
        Route::get('{paymentPlan}/edit', [App\Http\Controllers\FlexiblePaymentController::class, 'edit'])->name('edit');
        Route::put('{paymentPlan}', [App\Http\Controllers\FlexiblePaymentController::class, 'update'])->name('update');
        Route::delete('{paymentPlan}', [App\Http\Controllers\FlexiblePaymentController::class, 'destroy'])->name('destroy');
    });

    // PHASE 3: CURRENCY EXCHANGE ROUTES
    Route::prefix('currency-exchanges')->name('currency-exchanges.')->group(function() {
        Route::get('/', [App\Http\Controllers\CurrencyExchangeController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\CurrencyExchangeController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\CurrencyExchangeController::class, 'store'])->name('store');
        Route::get('{currencyExchange}', [App\Http\Controllers\CurrencyExchangeController::class, 'show'])->name('show');
        Route::get('{currencyExchange}/edit', [App\Http\Controllers\CurrencyExchangeController::class, 'edit'])->name('edit');
        Route::put('{currencyExchange}', [App\Http\Controllers\CurrencyExchangeController::class, 'update'])->name('update');
        Route::delete('{currencyExchange}', [App\Http\Controllers\CurrencyExchangeController::class, 'destroy'])->name('destroy');
        Route::get('api/conversion-rate', [App\Http\Controllers\CurrencyExchangeController::class, 'conversionRate'])->name('conversion_rate');
    });

    // PHASE 3: DIGITAL CONTRACTS ROUTES
    Route::prefix('digital-contracts')->name('digital-contracts.')->group(function() {
        Route::get('/', [App\Http\Controllers\DigitalContractController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\DigitalContractController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\DigitalContractController::class, 'store'])->name('store');
        Route::get('{contract}', [App\Http\Controllers\DigitalContractController::class, 'show'])->name('show');
        Route::get('{contract}/edit', [App\Http\Controllers\DigitalContractController::class, 'edit'])->name('edit');
        Route::put('{contract}', [App\Http\Controllers\DigitalContractController::class, 'update'])->name('update');
        Route::delete('{contract}', [App\Http\Controllers\DigitalContractController::class, 'destroy'])->name('destroy');
        Route::get('{contract}/pdf', [App\Http\Controllers\DigitalContractController::class, 'generatePdf'])->name('pdf');
        Route::post('{contract}/signature-request', [App\Http\Controllers\DigitalContractController::class, 'signatureRequest'])->name('signature_request');
        Route::get('{contract}/sign/{token}', [App\Http\Controllers\DigitalContractController::class, 'sign'])->name('sign');
        Route::post('{contract}/confirm-signature/{token}', [App\Http\Controllers\DigitalContractController::class, 'confirmSignature'])->name('confirm_signature');
    });

    // PHASE 3: ADVANCED REPORTS ROUTES
    Route::prefix('advanced-reports')->name('advanced-reports.')->group(function() {
        Route::get('/', [App\Http\Controllers\AdvancedReportController::class, 'index'])->name('index');
        Route::get('consultant-performance', [App\Http\Controllers\AdvancedReportController::class, 'consultantPerformance'])->name('consultant_performance');
        Route::get('seasonal-analysis', [App\Http\Controllers\AdvancedReportController::class, 'seasonalAnalysis'])->name('seasonal_analysis');
        Route::get('payment-analysis', [App\Http\Controllers\AdvancedReportController::class, 'paymentAnalysis'])->name('payment_analysis');
        Route::get('activity-dashboard', [App\Http\Controllers\AdvancedReportController::class, 'activityDashboard'])->name('activity_dashboard');
        Route::get('multi-dimensional', [App\Http\Controllers\AdvancedReportController::class, 'multiDimensionalReport'])->name('multi_dimensional');
        Route::post('export', [App\Http\Controllers\AdvancedReportController::class, 'exportReport'])->name('export');
    });

    // PHASE 5: HAKEDIŞ YÖNETIMI ROUTES
    Route::prefix('progress-payments')->name('progress-payments.')->group(function() {
        Route::get('/', [App\Http\Controllers\ProgressPaymentController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\ProgressPaymentController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\ProgressPaymentController::class, 'store'])->name('store');
        Route::get('{progressPayment}', [App\Http\Controllers\ProgressPaymentController::class, 'show'])->name('show');
        Route::get('{progressPayment}/edit', [App\Http\Controllers\ProgressPaymentController::class, 'edit'])->name('edit');
        Route::put('{progressPayment}', [App\Http\Controllers\ProgressPaymentController::class, 'update'])->name('update');
        Route::delete('{progressPayment}', [App\Http\Controllers\ProgressPaymentController::class, 'destroy'])->name('destroy');
        Route::post('{progressPayment}/approve', [App\Http\Controllers\ProgressPaymentController::class, 'approve'])->name('approve');
        Route::post('{progressPayment}/reject', [App\Http\Controllers\ProgressPaymentController::class, 'reject'])->name('reject');
        Route::post('{progressPayment}/submit', [App\Http\Controllers\ProgressPaymentController::class, 'submit'])->name('submit');
        Route::post('{progressPayment}/mark-as-paid', [App\Http\Controllers\ProgressPaymentController::class, 'markAsPaid'])->name('mark-as-paid');
        Route::get('reports/index', [App\Http\Controllers\ProgressPaymentController::class, 'reports'])->name('reports-index');
    });

    // PHASE 4: DATA IMPORT/EXPORT ROUTES
    Route::prefix('data-import-export')->name('data-import-export.')->group(function() {
        Route::get('/', [App\Http\Controllers\DataImportExportController::class, 'index'])->name('index');
        Route::get('import-form', [App\Http\Controllers\DataImportExportController::class, 'importForm'])->name('import-form');
        Route::post('import', [App\Http\Controllers\DataImportExportController::class, 'import'])->name('import');
        Route::get('export-form', [App\Http\Controllers\DataImportExportController::class, 'exportForm'])->name('export-form');
        Route::post('export', [App\Http\Controllers\DataImportExportController::class, 'export'])->name('export');
        Route::get('{import}', [App\Http\Controllers\DataImportExportController::class, 'show'])->name('show');
        Route::get('import-status/{import}', [App\Http\Controllers\DataImportExportController::class, 'importStatus'])->name('import-status');
        Route::get('download-error-report/{import}', [App\Http\Controllers\DataImportExportController::class, 'downloadErrorReport'])->name('download-error-report');
        Route::get('logs', [App\Http\Controllers\DataImportExportController::class, 'logs'])->name('logs');
        Route::delete('{import}', [App\Http\Controllers\DataImportExportController::class, 'destroy'])->name('destroy');
    });

    // PHASE 6: OPERASYON MODÜLÜ (Arsa/Emlak Yönetimi)
    Route::prefix('operations')->name('operations.')->group(function() {
        // Arsa/Emlak Yönetimi (Properties)
        Route::prefix('properties')->name('properties.')->group(function() {
            Route::get('/', [App\Http\Controllers\PropertyController::class, 'index'])->name('index');
            Route::get('create', [App\Http\Controllers\PropertyController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\PropertyController::class, 'store'])->name('store');
            Route::get('{property}', [App\Http\Controllers\PropertyController::class, 'show'])->name('show');
            Route::get('{property}/edit', [App\Http\Controllers\PropertyController::class, 'edit'])->name('edit');
            Route::put('{property}', [App\Http\Controllers\PropertyController::class, 'update'])->name('update');
            Route::delete('{property}', [App\Http\Controllers\PropertyController::class, 'destroy'])->name('destroy');

            // Belge Yönetimi
            Route::get('{property}/documents', [App\Http\Controllers\PropertyController::class, 'documents'])->name('documents');
            Route::post('{property}/documents/upload', [App\Http\Controllers\PropertyController::class, 'uploadDocument'])->name('documents.upload');

            // Yasal İşlem Yönetimi
            Route::post('{property}/legal-process', [App\Http\Controllers\PropertyController::class, 'addLegalProcess'])->name('legal-process.add');

            // Değerlendirme Yönetimi
            Route::post('{property}/evaluation', [App\Http\Controllers\PropertyController::class, 'addEvaluation'])->name('evaluation.add');

            // Hatırlatma Yönetimi (DocumentReminder)
            Route::post('document/{document}/reminder', [App\Http\Controllers\PropertyController::class, 'addReminder'])->name('reminder.add');

            // Toplu İşlemler
            Route::post('bulk-update-status', [App\Http\Controllers\PropertyController::class, 'bulkUpdateStatus'])->name('bulk.update-status');

            // Raporlar
            Route::get('{property}/evaluation-report', [App\Http\Controllers\PropertyController::class, 'evaluationReport'])->name('evaluation-report');
            Route::get('{property}/legal-status-report', [App\Http\Controllers\PropertyController::class, 'legalStatusReport'])->name('legal-status-report');
        });
    });


});

// TÜRKİYE İNŞAAT VE GAYRİMENKUL MEVZUATI 7 KRİTİK MODÜL ROTALARI
Route::middleware(['auth'])->group(function() {
    Route::resource('tasks', App\Http\Controllers\TaskController::class);
    Route::resource('notes', App\Http\Controllers\NoteController::class);
    Route::resource('gallery', App\Http\Controllers\GalleryController::class);
    Route::get('activities', [App\Http\Controllers\ActivityController::class, 'index'])->name('activities.index');

    // MESSAGES / MESAJLAŞMA ROTALARI
    Route::prefix('messages')->name('messages.')->group(function() {
        Route::get('/', [App\Http\Controllers\MessageController::class, 'index'])->name('index');
        Route::post('{id}/read', [App\Http\Controllers\MessageController::class, 'markAsRead'])->name('read');
        Route::get('{id}/reply', [App\Http\Controllers\MessageController::class, 'replyForm'])->name('reply');
        Route::post('{id}/reply', [App\Http\Controllers\MessageController::class, 'storeReply'])->name('storeReply');
        Route::delete('{id}', [App\Http\Controllers\MessageController::class, 'destroy'])->name('destroy');
    });
    Route::resource('sgk', App\Http\Controllers\SgkLaborController::class);
    Route::resource('land_owner_contracts', App\Http\Controllers\LandOwnerContractController::class);
    Route::post('land_owner_contracts/{contract}/add-matrix', [App\Http\Controllers\LandOwnerContractController::class, 'addUnitMatrix'])->name('land_owner_contracts.add_matrix');
    Route::resource('concrete_tests', App\Http\Controllers\ConcreteTestController::class);
    Route::resource('csb_poz', App\Http\Controllers\CsbPozController::class);
    Route::get('csb_poz-seed', [App\Http\Controllers\CsbPozController::class, 'seedSampleData'])->name('csb_poz.seed');
    Route::resource('promissory_notes', App\Http\Controllers\PromissoryNoteController::class);
    Route::resource('trade_assets', App\Http\Controllers\TradeAssetController::class);
    Route::resource('subcontractor_workers', App\Http\Controllers\SubcontractorWorkerController::class);
    Route::post('subcontractor_workers/{worker}/add-document', [App\Http\Controllers\SubcontractorWorkerController::class, 'addDocument'])->name('subcontractor_workers.add_document');

    // PERGEL İLERİ MODÜLLER (Mobil Saha Günlüğü, GIS Harita, Vade Zaman Çizelgesi, AI Risk Analizörü)
    Route::resource('site_diaries', App\Http\Controllers\SiteDiaryController::class);
    Route::get('projects-map', [App\Http\Controllers\ProjectMapController::class, 'index'])->name('projects.map');
    Route::get('promissory-notes-timeline', [App\Http\Controllers\PromissoryNoteTimelineController::class, 'index'])->name('promissory_notes.timeline');
    Route::get('contracts-ai-analysis', [App\Http\Controllers\ContractAiAnalysisController::class, 'index'])->name('land_owner_contracts.ai_analysis');

    // FAZ-2 (İLERİ SEVİYE) TÜRKİYE İNŞAAT MODÜLLERİ
    Route::resource('ewaybills', App\Http\Controllers\EWaybillController::class);
    Route::resource('building_inspections', App\Http\Controllers\BuildingInspectionController::class);
    Route::resource('subcontractor_deductions', App\Http\Controllers\SubcontractorDeductionController::class);
    Route::resource('price_differences', App\Http\Controllers\PriceDifferenceController::class);
    Route::resource('site_daily_logs', App\Http\Controllers\SiteDailyLogController::class);
    Route::resource('vat_returns', App\Http\Controllers\VatReturnController::class);
    Route::resource('real_estate_valuations', App\Http\Controllers\RealEstateValuationController::class);

    // FAZ-3 (YAPAY ZEKA, IoT & KURUMSAL) MODÜLLERİ
    Route::resource('fleet_telemetries', App\Http\Controllers\FleetTelemetryController::class);
    Route::resource('ai_timesheet_analyzers', App\Http\Controllers\AiTimesheetAnalyzerController::class);
    Route::resource('budget_simulators', App\Http\Controllers\BudgetSimulatorController::class);
    Route::resource('waste_managements', App\Http\Controllers\WasteManagementController::class);
    Route::resource('e_agreements', App\Http\Controllers\EAgreementController::class);

    // FAZ-4 (İŞ ZEKASI, YÖNETİM & CANLI İZLEME)
    Route::get('executive_dashboards', [App\Http\Controllers\ExecutiveDashboardController::class, 'index'])->name('executive_dashboards.index');
    Route::resource('site_cameras', App\Http\Controllers\SiteCameraController::class);
    Route::resource('subcontractor_scores', App\Http\Controllers\SubcontractorScoreController::class);
    Route::resource('hr_personnels', App\Http\Controllers\HrPersonnelController::class);
    Route::resource('dynamic_reports', App\Http\Controllers\DynamicReportController::class);
});

// Ana sayfa ve catch-all route: login olmayan kullanıcıya login ekranı göster (en sona taşındı)
Route::get('/{any?}', [App\Http\Controllers\HomeController::class, 'index'])->where('any', '.*')->name('index');




