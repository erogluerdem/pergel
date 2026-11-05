<?php
// Quick script to dump last 10 expenses from sqlite DB used by the app
$dbFile = __DIR__ . '/../database/database.sqlite';
if (!file_exists($dbFile)) {
    echo json_encode(['error' => 'DB file not found', 'path' => $dbFile]);
    exit(0);
}
try {
    $pdo = new PDO('sqlite:' . $dbFile);
    $stmt = $pdo->query("SELECT id, title, total_amount, expense_date, status, created_by, created_at FROM general_expenses ORDER BY id DESC LIMIT 10");
    $rows = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
