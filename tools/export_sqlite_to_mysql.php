<?php
// Export selected tables from SQLite DB to a MySQL-compatible INSERT-only SQL file.
// Usage: php export_sqlite_to_mysql.php

$sqlite = __DIR__ . '/../database/vouchers_test.sqlite';
$outFile = __DIR__ . '/../database/sql/vouchers_test_mysql_dump.sql';
$tables = ['customers', 'invoices', 'vouchers', 'voucher_lines'];

if (!file_exists($sqlite)) {
    echo "SQLite DB not found at: $sqlite\n";
    exit(1);
}

$pdo = new PDO('sqlite:' . $sqlite);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function escape_sql($v) {
    if (is_null($v)) return 'NULL';
    // numbers
    if (is_int($v) || is_float($v)) return (string)$v;
    // boolean
    if (is_bool($v)) return $v ? '1' : '0';
    // string
    return "'" . str_replace("'", "''", $v) . "'";
}

$out = "-- vouchers_test_mysql_dump.sql\n";
$out .= "-- Generated from database/vouchers_test.sqlite\n";
$out .= "-- Run the schema creation SQL first (e.g. vouchers_import.sql) or ensure tables exist.\n\n";

foreach ($tables as $table) {
    // check if table exists
    $res = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='" . $table . "'")->fetch(PDO::FETCH_ASSOC);
    if (!$res) {
        echo "Table $table not found in sqlite DB, skipping.\n";
        continue;
    }

    $rows = $pdo->query('SELECT * FROM ' . $table)->fetchAll(PDO::FETCH_ASSOC);
    if (!$rows) {
        echo "Table $table has 0 rows, skipping.\n";
        continue;
    }

    $out .= "-- Inserts for table $table\n";
    $columns = array_keys($rows[0]);
    $colList = '`' . implode('`,`', $columns) . '`';

    // Batch INSERTs for performance
    $batchSize = 50;
    $batches = array_chunk($rows, $batchSize);
    foreach ($batches as $batch) {
        $values = [];
        foreach ($batch as $row) {
            $vals = array_map('escape_sql', array_values($row));
            $values[] = '(' . implode(', ', $vals) . ')';
        }
        $out .= "INSERT INTO `$table` ($colList) VALUES\n" . implode(",\n", $values) . ";\n\n";
    }
}

file_put_contents($outFile, $out);
echo "Wrote SQL dump to: $outFile\n";
