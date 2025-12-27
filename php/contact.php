<?php
// contact.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$required = ['name', 'email', 'message'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => "$field is required"]);
        exit;
    }
}

// Validate email
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

try {
    // Get client info
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    // Prepare SQL statement
    $stmt = $pdo->prepare("
        INSERT INTO contacts 
        (name, email, subject, message, ip_address, user_agent) 
        VALUES (:name, :email, :subject, :message, :ip, :agent)
    ");
    
    // Execute with parameters
    $result = $stmt->execute([
        ':name' => htmlspecialchars(trim($data['name'])),
        ':email' => filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL),
        ':subject' => isset($data['subject']) ? htmlspecialchars(trim($data['subject'])) : 'No Subject',
        ':message' => htmlspecialchars(trim($data['message'])),
        ':ip' => $ip_address,
        ':agent' => $user_agent
    ]);
    
    if ($result) {
        // Optional: Send email notification
        sendEmailNotification($data);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Message sent successfully!',
            'id' => $pdo->lastInsertId()
        ]);
    } else {
        throw new Exception('Failed to save contact message');
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

function sendEmailNotification($data) {
    // Configure these for your server
    $to = "admin@mmustcu.org"; // Your admin email
    $subject = "New Contact Form Submission: " . ($data['subject'] ?? 'No Subject');
    
    $message = "New contact form submission:\n\n";
    $message .= "Name: " . $data['name'] . "\n";
    $message .= "Email: " . $data['email'] . "\n";
    $message .= "Subject: " . ($data['subject'] ?? 'No Subject') . "\n";
    $message .= "Message:\n" . $data['message'] . "\n\n";
    $message .= "IP Address: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "\n";
    $message .= "Time: " . date('Y-m-d H:i:s');
    
    $headers = "From: no-reply@mmustcu.org\r\n";
    $headers .= "Reply-To: " . $data['email'] . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Uncomment to enable email sending
    // mail($to, $subject, $message, $headers);
}
?>