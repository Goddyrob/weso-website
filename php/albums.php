<?php
// php/albums.php
// Simple JSON API that returns active media albums from the local MySQL database.
// This replaces Supabase by using the project's existing PHP DB connection.

require __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $pdo->prepare('SELECT id, title, description, folder_id, category, event_date, cover_image, is_active FROM media_albums WHERE is_active = 1 ORDER BY event_date DESC');
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Normalize fields for frontend expectations
    foreach ($rows as &$r) {
        // Ensure folder_id is a string
        $r['folder_id'] = isset($r['folder_id']) ? (string)$r['folder_id'] : '';
        // Ensure cover_image exists (can be empty string)
        $r['cover_image'] = isset($r['cover_image']) ? $r['cover_image'] : '';
        // JSON encode nulls as empty strings for safer client handling
        if ($r['description'] === null) $r['description'] = '';
    }
    echo json_encode($rows);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to load albums']);
}

?>
