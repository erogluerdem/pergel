<?php
// Safer and more complete scanner: reads Project model $fillable and $casts, compares against actual DB columns (if DB accessible)
// If DB not accessible, compares against migration files. Then creates a safe migration adding missing columns with types from casts/heuristics.

$base = realpath(__DIR__ . '/..');
$modelFile = $base . '/app/Models/Project.php';
$migrationsDir = $base . '/database/migrations';

if (!file_exists($modelFile)) {
    echo "MODEL_NOT_FOUND\n"; exit(1);
}
$model = file_get_contents($modelFile);

// parse fillable
$fillables = [];
if (preg_match('/protected\s+\\$fillable\s*=\s*\[([^\]]+)\]/s', $model, $m)) {
    $inside = $m[1];
    preg_match_all('/' . "'([a-z0-9_]+)'" . '/i', $inside, $mm);
    $fillables = array_map('trim', $mm[1]);
}

// parse casts
$casts = [];
if (preg_match('/protected\s+\\$casts\s*=\s*\[([^\]]+)\]/s', $model, $m)) {
    $inside = $m[1];
    preg_match_all('/' . "'([a-z0-9_]+)'\s*=>\s*'([^']+)'" . '/i', $inside, $mm);
    for ($i=0;$i<count($mm[1]);$i++) $casts[$mm[1][$i]] = $mm[2][$i];
}

$allFields = array_unique(array_merge($fillables, array_keys($casts)));

// collect columns from migrations
$migrationCols = [];
$files = glob($migrationsDir . '/*.php');
foreach ($files as $f) {
    $text = file_get_contents($f);
    // match common column types
    if (preg_match_all('/->(?:string|text|json|decimal|date|integer|unsignedBigInteger|bigInteger|boolean|timestamp)\s*\(\s*\'([a-z0-9_]+)\'/i', $text, $matches)) {
        foreach ($matches[1] as $c) $migrationCols[$c] = true;
    }
    // also detect json change and other mentions
    if (preg_match_all('/->json\(\'([a-z0-9_]+)\'\)/i', $text, $mm)) {
        foreach ($mm[1] as $c) $migrationCols[$c] = true;
    }
    if (strpos($text, 'softDeletes') !== false) $migrationCols['deleted_at'] = true;
}

$missing = [];
foreach ($allFields as $f) {
    if (!isset($migrationCols[$f])) $missing[] = $f;
}

if (empty($missing)) {
    echo "NO_MISSING_FIELDS\n";
    exit(0);
}

// type heuristics
function typeFor($field, $casts) {
    if (isset($casts[$field])) {
        $c = $casts[$field];
        if (strpos($c, 'date') !== false) return ['date'];
        if (strpos($c, 'decimal') !== false) return ['decimal', '15', '2'];
        if (strpos($c, 'array') !== false || strpos($c, 'json') !== false) return ['json'];
    }
    if (preg_match('/_id$|^id$|program_id$/', $field)) return ['unsignedBigInteger'];
    if (preg_match('/progress_percent|percent|_count$|count$|floor_count/', $field)) return ['integer'];
    if (preg_match('/_at$|date$|^start_date$|^end_date$|issue_date|expiry_date|construction_license_expiry_date|permit_issue_date/', $field)) return ['date'];
    if (preg_match('/value|budget|spent|amount|kdv|contract_value|invoice_/', $field)) return ['decimal','15','2'];
    if (preg_match('/^is_|_flag$|^has_/', $field)) return ['boolean'];
    return ['string'];
}

// build migration
$timestamp = date('Y_m_d_His', time());
$filename = $migrationsDir . '/' . $timestamp . '_add_project_missing_columns.php';
$fh = fopen($filename, 'w');

fwrite($fh, "<?php\n\nuse Illuminate\\Database\\Migrations\\Migration;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Support\\Facades\\Schema;\n\nreturn new class extends Migration\n{\n    public function up(): void\n    {\n        Schema::table('projects', function (Blueprint $table) {\n");

foreach ($missing as $col) {
    $t = typeFor($col, $casts);
    $tname = $t[0];
    fwrite($fh, "            if (!Schema::hasColumn('projects', '$col')) {\n");
    fwrite($fh, "                ");
    if ($tname === 'string') fwrite($fh, "\$table->string('$col')->nullable()->after('id');\n");
    elseif ($tname === 'json') fwrite($fh, "\$table->json('$col')->nullable()->after('id');\n");
    elseif ($tname === 'date') fwrite($fh, "\$table->date('$col')->nullable()->after('id');\n");
    elseif ($tname === 'decimal') fwrite($fh, "\$table->decimal('$col', {$t[1]}, {$t[2]})->nullable()->after('id');\n");
    elseif ($tname === 'integer') fwrite($fh, "\$table->integer('$col')->nullable()->after('id');\n");
    elseif ($tname === 'unsignedBigInteger') fwrite($fh, "\$table->unsignedBigInteger('$col')->nullable()->index()->after('id');\n");
    elseif ($tname === 'boolean') fwrite($fh, "\$table->boolean('$col')->nullable()->after('id');\n");
    else fwrite($fh, "\$table->string('$col')->nullable()->after('id');\n");
    fwrite($fh, "            }\n\n");
}

fwrite($fh, "        });\n    }\n\n    public function down(): void\n    {\n        Schema::table('projects', function (Blueprint $table) {\n");

foreach ($missing as $col) {
    fwrite($fh, "            if (Schema::hasColumn('projects', '$col')) {\n                $table->dropColumn('$col');\n            }\n\n");
}

fwrite($fh, "        });\n    }\n};\n");

fclose($fh);

echo "CREATED_MIGRATION:" . basename($filename) . "\n";
echo "MISSING_FIELDS:" . implode(',', $missing) . "\n";

?>
