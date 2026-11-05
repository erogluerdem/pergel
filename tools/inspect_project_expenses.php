<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;
use Illuminate\Support\Facades\DB;

$projectId = $argv[1] ?? 7;
$p = Project::find($projectId);
if (!$p) {
    echo "Project not found: $projectId\n";
    exit(1);
}
$code = $p->project_code ?? null;
echo "project_id={$p->id}\n";
echo "project_code=" . ($code === null ? 'NULL' : $code) . "\n";
if (!$code) {
    echo "No project_code set for this project. If expenses use project_code mapping, they won't show.\n";
    exit(0);
}

$rows = DB::table('general_expenses')
    ->where('project_code', $code)
    ->whereNotNull('expense_date')
    ->where('status', '!=', 'rejected')
    ->orderBy('expense_date','desc')
    ->limit(5)
    ->get();
$count = DB::table('general_expenses')
    ->where('project_code', $code)
    ->whereNotNull('expense_date')
    ->where('status', '!=', 'rejected')
    ->count();

echo "matched_expense_count={$count}\n";
if ($count>0) {
    echo "examples:\n";
    foreach ($rows as $r) {
        echo json_encode([ 'id'=>$r->id, 'expense_date'=>$r->expense_date, 'category'=>$r->category, 'amount'=>$r->amount, 'total_amount'=>$r->total_amount, 'status'=>$r->status ]) . "\n";
    }
}

// Also check expenses that might use project_id instead
$altCount = DB::table('general_expenses')->where('project_id', $p->id)->count();
echo "expenses_with_project_id_field={$altCount}\n";
