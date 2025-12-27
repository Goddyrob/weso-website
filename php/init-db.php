<?php
// init-db.php - Run the init.sql to create tables
require_once __DIR__ . '/db.php';

try {
    $sql = file_get_contents(__DIR__ . '/init.sql');
    $pdo->exec($sql);
    echo "✅ Database initialized successfully!";
} catch (Exception $e) {
    echo "❌ Error initializing database: " . $e->getMessage();
}
?>