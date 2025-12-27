<?php
// Use the shared DB connection from php/db.php
require_once __DIR__ . '/../php/db.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;

header('Content-Type: application/json');

switch($action) {
    case 'albums':
        $stmt = $pdo->query("SELECT * FROM albums ORDER BY created_at DESC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;
        
    case 'album':
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM albums WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        }
        break;

    case 'album_photos':
        // Returns an array of photos for a given Google Drive folder_id
        $folderId = $_GET['folder_id'] ?? null;
        $debug = isset($_GET['debug']) && $_GET['debug'] == '1';
        $debugInfo = [];
        if (!$folderId) {
            echo json_encode([]);
            break;
        }

        // Returns image files from a Drive folder. Prefer service-account OAuth2 token
        // when available; otherwise fall back to DRIVE_API_KEY public listing.
        $folderId = $_GET['folder_id'] ?? null;

        // Helper: base64url encode
        $base64url = function($data) {
            return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
        };

        // Helper: obtain access token using service account JSON
        $getServiceAccountAccessToken = function() use ($base64url) {
            // Check env var with raw JSON
            $saJson = getenv('SERVICE_ACCOUNT_JSON') ?: '';
            if (empty($saJson)) {
                // Try path in env
                $saPath = getenv('SERVICE_ACCOUNT_JSON_PATH') ?: __DIR__ . '/../service-account.json';
                if (file_exists($saPath)) {
                    $saJson = file_get_contents($saPath);
                }
            }

            if (empty($saJson)) return null;

            $sa = json_decode($saJson, true);
            if (empty($sa['client_email']) || empty($sa['private_key']) || empty($sa['token_uri'])) return null;

            $now = time();
            $header = $base64url(json_encode(['alg'=>'RS256','typ'=>'JWT']));
            $claims = [
                'iss' => $sa['client_email'],
                'scope' => 'https://www.googleapis.com/auth/drive.readonly',
                'aud' => $sa['token_uri'],
                'exp' => $now + 3600,
                'iat' => $now,
            ];

            $payload = $base64url(json_encode($claims));
            $unsigned = $header . '.' . $payload;

            $pkey = openssl_pkey_get_private($sa['private_key']);
            if (!$pkey) return null;

            $signature = '';
            $ok = openssl_sign($unsigned, $signature, $pkey, OPENSSL_ALGO_SHA256);
            openssl_free_key($pkey);
            if (!$ok) return null;

            $signed = $base64url($signature);
            $jwt = $unsigned . '.' . $signed;

            // Exchange JWT for access token
            $post = http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'content' => $post,
                    'timeout' => 15,
                ]
            ]);

            $resp = @file_get_contents($sa['token_uri'], false, $context);
            if (!$resp) return null;
            $data = json_decode($resp, true);
            return $data['access_token'] ?? null;
        };

        // Prefer service account token if available
        $accessToken = $getServiceAccountAccessToken();
        if ($debug) {
            $debugInfo['service_account_token_obtained'] = $accessToken ? true : false;
        }

        $photos = [];
        if ($accessToken) {
            // Call Drive API with Authorization header
            $apiUrl = 'https://www.googleapis.com/drive/v3/files';
            $q = sprintf("'%s' in parents and trashed=false", $folderId);
            $params = [
                'q' => $q,
                'fields' => 'files(id,name,mimeType,thumbnailLink,webContentLink)',
                'pageSize' => 1000,
            ];
            $url = $apiUrl . '?' . http_build_query($params);

            if (function_exists('curl_version')) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
                curl_setopt($ch, CURLOPT_TIMEOUT, 15);
                $resp = curl_exec($ch);
                $curlErr = curl_error($ch);
                $curlInfo = curl_getinfo($ch);
                curl_close($ch);
                if ($debug) {
                    $debugInfo['curl_info'] = $curlInfo;
                    $debugInfo['curl_error'] = $curlErr ?: null;
                }
            } else {
                $opts = ['http' => ['method' => 'GET', 'header' => 'Authorization: Bearer ' . $accessToken . "\r\n", 'timeout' => 15]];
                $resp = @file_get_contents($url, false, stream_context_create($opts));
                if ($debug && isset($http_response_header)) {
                    $debugInfo['http_response_header'] = $http_response_header;
                }
            }

            if ($resp) {
                $data = json_decode($resp, true);
                if (!empty($data['files']) && is_array($data['files'])) {
                    foreach ($data['files'] as $file) {
                        if (strpos($file['mimeType'] ?? '', 'image/') !== 0) continue;
                        $id = $file['id'];
                        $url = $file['webContentLink'] ?? ('https://drive.google.com/uc?export=view&id=' . $id);
                        $photos[] = ['id'=>$id,'name'=>$file['name'] ?? '','url'=>$url,'thumbnail'=>$file['thumbnailLink'] ?? null];
                    }
                }
                if ($debug) {
                    $debugInfo['drive_api_files_count'] = isset($data['files']) ? count($data['files']) : 0;
                }
            }
        }

        // Fallback: try API key public listing if no photos yet
        if (empty($photos)) {
            $driveApiKey = getenv('DRIVE_API_KEY') ?: '';
            if (!empty($driveApiKey)) {
                $q = sprintf("'%s' in parents and trashed=false", $folderId);
                $params = http_build_query([
                    'q' => $q,
                    'fields' => 'files(id,name,mimeType,thumbnailLink,webContentLink)',
                    'pageSize' => 1000,
                    'key' => $driveApiKey
                ]);

                $apiUrl = 'https://www.googleapis.com/drive/v3/files?' . $params;
                if (function_exists('curl_version')) {
                    $ch = curl_init($apiUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                    $resp = curl_exec($ch);
                    curl_close($ch);
                } else {
                    $resp = @file_get_contents($apiUrl);
                }

                if ($resp) {
                    $data = json_decode($resp, true);
                    if ($debug) {
                        $debugInfo['api_key_listing_response'] = is_array($data) ? $data : $resp;
                        $debugInfo['api_key_files_count'] = isset($data['files']) ? count($data['files']) : 0;
                    }
                    if (!empty($data['files']) && is_array($data['files'])) {
                        foreach ($data['files'] as $file) {
                            if (strpos($file['mimeType'] ?? '', 'image/') !== 0) continue;
                            $id = $file['id'];
                            $url = $file['webContentLink'] ?? ('https://drive.google.com/uc?export=view&id=' . $id);
                            $photos[] = ['id'=>$id,'name'=>$file['name'] ?? '','url'=>$url,'thumbnail'=>$file['thumbnailLink'] ?? null];
                        }
                    }
                }
            }
        }

        if ($debug) {
            echo json_encode(['photos'=>$photos,'debug'=>$debugInfo]);
        } else {
            echo json_encode($photos);
        }
        break;
        
    case 'sermons':
        $stmt = $pdo->query("SELECT * FROM sermons ORDER BY created_at DESC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;
        
    case 'sermon':
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM sermons WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        }
        break;
        
    case 'videos':
        $stmt = $pdo->query("SELECT * FROM videos ORDER BY created_at DESC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;
        
    case 'video':
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM videos WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        }
        break;
        
    // ... handle other actions ...
        
    default:
        echo json_encode([]);
        break;
}
?>