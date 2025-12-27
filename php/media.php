<?php
// php/media.php
// Reads the saved Google Drive folder URL from `media_settings` and embeds the folder view.
// This approach uses Drive's embedded folder view which shows thumbnails when the folder
// is shared publicly (Anyone with the link can view).

require __DIR__ . '/db.php';

$sel = $pdo->prepare('SELECT setting_value FROM media_settings WHERE setting_key = :k LIMIT 1');
$sel->execute([':k' => 'drive_folder_url']);
$driveUrl = $sel->fetchColumn() ?: '';

function extractDriveFolderId($url) {
  if (!$url) return null;
  if (preg_match('#/folders/([a-zA-Z0-9_-]+)#', $url, $m)) return $m[1];
  if (preg_match('#/drive/u/\d+/folders/([a-zA-Z0-9_-]+)#', $url, $m)) return $m[1];
  if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $url, $m)) return $m[1];
  if (preg_match('#/file/d/([a-zA-Z0-9_-]+)#', $url, $m)) return $m[1];
  return null;
}

$folderId = extractDriveFolderId($driveUrl);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Media Gallery</title>
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>
  <main style="max-width:1100px;margin:2rem auto;padding:1rem;">
    <h1>Media Gallery</h1>

    <?php if (!$folderId): ?>
      <p style="color:#666;">No Google Drive folder configured. If you're an admin, set the folder URL in the <a href="/php/admin.php">admin panel</a>.</p>
    <?php else: ?>
      <p>Showing images from the Drive folder. If the folder is large, consider using the Drive API or a CDN for performance.</p>
      <iframe src="https://drive.google.com/embeddedfolderview?id=<?php echo htmlspecialchars($folderId); ?>#grid" style="width:100%;height:720px;border:0;"></iframe>
    <?php endif; ?>

  </main>
</body>
</html>
