<?php
// Scans Project model fillable + casts and existing migrations to find missing columns
// Then emits a safe migration file adding missing columns with simple type heuristics.

$base = realpath(__DIR__ . '/..');
$modelFile = $base . '/app/Models/Project.php';
$migrationsDir = $base . '/database/migrations';

if (!file_exists($modelFile)) {
    echo "MODEL_NOT_FOUND\n"; exit(1);
}
$model = file_get_contents($modelFile);

// Extract fillable array entries
$fillables = [];
if (preg_match('/protected\s+\\$fillable\s*=\s*\[([^\]]+)\]/s', $model, $m)) {
    $inside = $m[1];
    preg_match_all('/' . "'([a-z0-9_]+)'" . '/i', $inside, $mm);
    $fillables = array_map('trim', $mm[1]);
}

// Extract casts keys
$casts = [];
if (preg_match('/protected\s+\\$casts\s*=\s*\[([^\]]+)\]/s', $model, $m)) {
    $inside = $m[1];
    preg_match_all('/' . "'([a-z0-9_]+)'\s*=>\s*'([^']+)'" . '/i', $inside, $mm);
    for ($i=0;$i<count($mm[1]);$i++) {
        $casts[$mm[1][$i]] = $mm[2][$i];
    }
}

// Collect columns mentioned in migrations
$migrationCols = [];
$files = glob($migrationsDir . '/*.php');
foreach ($files as $f) {
    $text = file_get_contents($f);
    // match ->string('name'
    if (preg_match_all('/->(?:string|text|json|decimal|date|integer|unsignedBigInteger|bigInteger|boolean|timestamp)\s*\(\s*\'([a-z0-9_]+)\'/i', $text, $matches)) {
        foreach ($matches[1] as $c) $migrationCols[$c] = true;
    }
    // softDeletes -> deleted_at
    if (strpos($text, 'softDeletes') !== false) $migrationCols['deleted_at'] = true;
    // PRAGMA: also look for dropColumn lists to know columns referenced (skipped)
}

// Build missing list = fillables - migrationCols
$missing = [];
foreach ($fillables as $f) {
    if (!isset($migrationCols[$f])) $missing[] = $f;
}

if (empty($missing)) {
    echo "NO_MISSING\n";
    exit(0);
}

// Heuristic type selection
function typeFor($field, $casts) {
    if (isset($casts[$field])) {
        $c = $casts[$field];
        if (strpos($c, 'date') !== false) return ['date'];
        if (strpos($c, 'decimal') !== false) return ['decimal', '15', '2'];
        if (strpos($c, 'array') !== false || strpos($c, 'json') !== false) return ['json'];
    }
    if (preg_match('/_id$/', $field)) return ['unsignedBigInteger'];
    if (preg_match('/count$|_count$|floor_count|progress_percent|percent/', $field)) return ['integer'];
    if (preg_match('/_at$|date$|date_/', $field)) return ['date'];
    if (preg_match('/value|budget|spent|amount|kdv|contract_value/', $field)) return ['decimal','15','2'];
    return ['string'];
}

// Build migration content
$timestamp = date('Y_m_d_His', time());
$filename = $migrationsDir . '/' . $timestamp . '_add_missing_project_columns.php';

$lines = [];
$lines[] = "<?php\n";
$lines[] = "use Illuminate\\Database\\Migrations\\Migration;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Support\\Facades\\Schema;\n\nreturn new class extends Migration\n{\n    public function up(): void\n    {\n        Schema::table('projects', function (Blueprint $table) {\n";

foreach ($missing as $col) {
    $t = typeFor($col, $casts);
    $tname = $t[0];
    $add = "            if (!Schema::hasColumn('projects', '$col')) {\n                Schema::table('projects', function (Blueprint $table) {\n";
    if ($tname === 'string') {
        $add .= "                    $table->string('$col')->nullable();\n";
    } elseif ($tname === 'json') {
        $add .= "                    $table->json('$col')->nullable();\n";
    } elseif ($tname === 'date') {
        $add .= "                    $table->date('$col')->nullable();\n";
    } elseif ($tname === 'decimal') {
        $p = $t[1] ?? 15; $s = $t[2] ?? 2;
        $add .= "                    $table->decimal('$col', $p, $s)->nullable();\n";
    } elseif ($tname === 'integer') {
        $add .= "                    $table->integer('$col')->nullable();\n";
    } elseif ($tname === 'unsignedBigInteger') {
        $add .= "                    $table->unsignedBigInteger('$col')->nullable()->index();\n";
    } elseif ($tname === 'boolean') {
        $add .= "                    $table->boolean('$col')->nullable();\n";
    } else {
        $add .= "                    $table->string('$col')->nullable();\n";
    }
    $add .= "                });\n            }\n";
    $lines[] = $add;
}

$lines[] = "        });\n    }\n\n    public function down(): void\n    {\n        Schema::table('projects', function (Blueprint $table) {\n";
foreach ($missing as $col) {
    $lines[] = "            if (Schema::hasColumn('projects', '$col')) {\n                $table->dropColumn('$col');\n            }\n";
}
$lines[] = "        });\n    }\n};\n";

file_put_contents($filename, implode("", $lines));

echo "CREATED_MIGRATION:" . basename($filename) . "\n";
echo "MISSING:" . implode(',', $missing) . "\n";

?>
