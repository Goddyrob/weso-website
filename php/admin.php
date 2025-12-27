<?php
// php/admin.php
// Simple admin page to set the Google Drive folder URL used by the media viewer.
// NOTE: This is not an authenticated admin area. In production, protect this page with authentication.

require __DIR__ . '/db.php';

$errors = [];
$success = false;

// Load current setting
$sel = $pdo->prepare('SELECT setting_value FROM media_settings WHERE setting_key = :k LIMIT 1');
$sel->execute([':k' => 'drive_folder_url']);
$current = $sel->fetchColumn() ?: '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = trim($_POST['drive_folder_url'] ?? '');

    // Simple URL validation
    if ($url !== '' && !filter_var($url, FILTER_VALIDATE_URL)) {
        $errors[] = 'Please enter a valid URL.';
    }

    if (empty($errors)) {
        try {
            // Upsert pattern: try update, if 0 rows then insert
            $stmt = $pdo->prepare('UPDATE media_settings SET setting_value = :v WHERE setting_key = :k');
            $stmt->execute([':v' => $url, ':k' => 'drive_folder_url']);
            if ($stmt->rowCount() === 0) {
                $ins = $pdo->prepare('INSERT INTO media_settings (setting_key, setting_value) VALUES (:k, :v)');
                $ins->execute([':k' => 'drive_folder_url', ':v' => $url]);
            }
            $success = true;
            $current = $url;
        } catch (PDOException $e) {
            $errors[] = 'Failed to save setting.';
        }
    }
}

// Helper: extract folder ID from Drive URL (handles several common variants)
function extractDriveFolderId($url) {
  if (!$url) return null;
  // common pattern: /folders/FILEID (also handles /drive/u/0/folders/FILEID)
  if (preg_match('#/folders/([a-zA-Z0-9_-]+)#', $url, $m)) return $m[1];
  if (preg_match('#/drive/u/\d+/folders/([a-zA-Z0-9_-]+)#', $url, $m)) return $m[1];
  // sometimes shared via id=... param
  if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $url, $m)) return $m[1];
  // rare: file share style (not typical for folders) - included for robustness
  if (preg_match('#/file/d/([a-zA-Z0-9_-]+)#', $url, $m)) return $m[1];
  return null;
}

$folderId = extractDriveFolderId($current);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Media Admin - Drive Folder</title>
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>
  <main style="max-width:800px;margin:2rem auto;padding:1rem;">
    <h1>Media Folder Setting</h1>

    <?php if ($success): ?>
      <div style="background:#d4edda;padding:1rem;border-radius:6px;color:#155724;margin-bottom:1rem;">Saved successfully.</div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <div style="background:#f8d7da;padding:1rem;border-radius:6px;color:#721c24;margin-bottom:1rem;">
        <ul>
        <?php foreach ($errors as $err): ?>
          <li><?php echo htmlspecialchars($err); ?></li>
        <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
      <div style="margin-bottom:0.75rem;">
        <label for="drive_folder_url">Google Drive Folder URL</label><br>
        <input id="drive_folder_url" name="drive_folder_url" value="<?php echo htmlspecialchars($current); ?>" placeholder="https://drive.google.com/drive/folders/XXXXXXXXXXXX" style="width:100%;padding:0.5rem;">
      </div>

      <button type="submit" style="padding:0.6rem 1rem;">Save</button>
    </form>

    <hr style="margin:1.5rem 0;">

    <h2>Preview</h2>
    <?php if ($folderId): ?>
      <p>Detected folder ID: <code><?php echo htmlspecialchars($folderId); ?></code></p>
      <p>Embed preview:</p>
      <iframe src="https://drive.google.com/embeddedfolderview?id=<?php echo htmlspecialchars($folderId); ?>#grid" style="width:100%;height:480px;border:0;"></iframe>
    <?php else: ?>
      <p style="color:#666;">Enter a shareable Google Drive folder URL above (folder must be "Anyone with the link" &gt; Viewer). After saving, a preview will appear here.</p>
    <?php endif; ?>

    <p style="margin-top:1rem;color:#666;font-size:0.9rem;">Note: For direct image thumbnails or advanced control, use the Google Drive API (requires OAuth/API key) or host images on a static CDN.</p>
  </main>
</body>
</html>
