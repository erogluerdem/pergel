<?php
$db = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'database.sqlite';
if (!file_exists($db)) {
    echo "NO_DB_FILE\n";
    exit(0);
}
try {
    $pdo = new PDO('sqlite:'.$db);
    $res = $pdo->query("PRAGMA table_info('projects')");
    if (!$res) {
        echo "NO_TABLE\n";
        exit(0);
    }
    $cols = $res->fetchAll(PDO::FETCH_ASSOC);
    if (empty($cols)) {
        echo "NO_COLUMNS\n";
        exit(0);
    }
    foreach ($cols as $c) {
        echo $c['name'] . "\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
