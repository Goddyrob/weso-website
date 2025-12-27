<?php
// Use shared DB connection
require_once __DIR__ . '/../php/db.php';

// If $pdo is not available for some reason, return error
if (!isset($pdo)) {
    echo json_encode(['success' => false, 'error' => 'Database connection not available']);
    exit;
}

// Get the request data
$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';
$id = $data['id'] ?? null;
$itemData = $data['data'] ?? [];

header('Content-Type: application/json');
// Wrap operations so any unexpected exceptions return JSON
try {
    switch($action) {
    case 'save_album':
        if ($id) {
            // Update existing album
                    $sql = "UPDATE albums SET 
                    title = :title,
                    folder_id = :folder_id,
                    description = :description,
                    category = :category,
                    event_date = :event_date,
                    cover_image = :cover_image,
                    updated_at = NOW()
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $params = [
                ':title' => $itemData['title'],
                ':folder_id' => $itemData['folder_id'],
                ':description' => $itemData['description'],
                ':category' => $itemData['category'],
                ':event_date' => $itemData['event_date'],
                ':cover_image' => $itemData['cover_image'],
                ':id' => $id
            ];
            $stmt->execute($params);
            echo json_encode(['success' => true, 'message' => 'Album updated successfully']);
        } else {
            // Insert new album
                    $sql = "INSERT INTO albums (title, folder_id, description, category, event_date, cover_image, is_active, created_at) 
                    VALUES (:title, :folder_id, :description, :category, :event_date, :cover_image, 1, NOW())";
            $stmt = $pdo->prepare($sql);
            $params = [
                ':title' => $itemData['title'],
                ':folder_id' => $itemData['folder_id'],
                ':description' => $itemData['description'],
                ':category' => $itemData['category'],
                ':event_date' => $itemData['event_date'],
                ':cover_image' => $itemData['cover_image']
            ];
            $stmt->execute($params);
            echo json_encode(['success' => true, 'message' => 'Album created successfully']);
        }
        break;
        
    case 'toggle_album_status':
        $sql = "UPDATE albums SET is_active = :status WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':status' => $data['status'] ? 1 : 0, ':id' => $id]);
        echo json_encode(['success' => true, 'message' => 'Album status updated']);
        break;
        
    case 'delete_album':
        $sql = "DELETE FROM albums WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        echo json_encode(['success' => true, 'message' => 'Album deleted']);
        break;
        
    case 'save_sermon':
        if ($id) {
            // Update existing sermon
            $sql = "UPDATE sermons SET 
                    title = :title,
                    speaker = :speaker,
                    day = :day,
                    date = :date,
                    pdf_url = :pdf_url,
                    ppt_url = :ppt_url,
                    updated_at = NOW()
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $params = [
                ':title' => $itemData['title'],
                ':speaker' => $itemData['speaker'],
                ':day' => $itemData['day'],
                ':date' => $itemData['date'],
                ':pdf_url' => $itemData['pdf_url'],
                ':ppt_url' => $itemData['ppt_url'],
                ':id' => $id
            ];
            $stmt->execute($params);
            echo json_encode(['success' => true, 'message' => 'Sermon updated successfully']);
        } else {
            // Insert new sermon
            $sql = "INSERT INTO sermons (title, speaker, day, date, pdf_url, ppt_url, is_active, created_at) 
                    VALUES (:title, :speaker, :day, :date, :pdf_url, :ppt_url, 1, NOW())";
            $stmt = $pdo->prepare($sql);
            $params = [
                ':title' => $itemData['title'],
                ':speaker' => $itemData['speaker'],
                ':day' => $itemData['day'],
                ':date' => $itemData['date'],
                ':pdf_url' => $itemData['pdf_url'],
                ':ppt_url' => $itemData['ppt_url']
            ];
            $stmt->execute($params);
            echo json_encode(['success' => true, 'message' => 'Sermon created successfully']);
        }
        break;
        
    case 'save_video':
        if ($id) {
            // Update existing video
            $sql = "UPDATE videos SET 
                    title = :title,
                    url = :url,
                    description = :description,
                    date = :date,
                    updated_at = NOW()
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $params = [
                ':title' => $itemData['title'],
                ':url' => $itemData['url'],
                ':description' => $itemData['description'],
                ':date' => $itemData['date'],
                ':id' => $id
            ];
            $stmt->execute($params);
            echo json_encode(['success' => true, 'message' => 'Video updated successfully']);
        } else {
            // Insert new video
            $sql = "INSERT INTO videos (title, url, description, date, is_active, created_at) 
                    VALUES (:title, :url, :description, :date, 1, NOW())";
            $stmt = $pdo->prepare($sql);
            $params = [
                ':title' => $itemData['title'],
                ':url' => $itemData['url'],
                ':description' => $itemData['description'],
                ':date' => $itemData['date']
            ];
            $stmt->execute($params);
            echo json_encode(['success' => true, 'message' => 'Video created successfully']);
        }
        break;
        
    case 'toggle_video_status':
        $sql = "UPDATE videos SET is_active = :status WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':status' => $data['status'] ? 1 : 0, ':id' => $id]);
        echo json_encode(['success' => true, 'message' => 'Video status updated']);
        break;
        
    case 'delete_video':
        $sql = "DELETE FROM videos WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        echo json_encode(['success' => true, 'message' => 'Video deleted']);
        break;
        
    // Add similar cases for articles...
        
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
        break;
    }
} catch (PDOException $e) {
    // Return DB error details to the admin UI for debugging (safe in local/dev)
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
    exit;
}
?>