<?php
// register.php - WESO Registration Handler
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$dbname = 'weso_db';
$username = 'root';
$password = ''; // Change if you have a password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Get POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// If JSON decoding fails, try form data
if ($data === null) {
    $data = $_POST;
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Map field names (handles both naming conventions)
$full_name = isset($data['full_name']) ? trim($data['full_name']) : (isset($data['fullName']) ? trim($data['fullName']) : '');
$email = isset($data['email_address']) ? trim($data['email_address']) : (isset($data['email']) ? trim($data['email']) : '');
$phone = isset($data['phone_number']) ? trim($data['phone_number']) : (isset($data['phone']) ? trim($data['phone']) : '');
$gender = isset($data['gender']) ? trim($data['gender']) : '';
$church = isset($data['church_fellowship']) ? trim($data['church_fellowship']) : (isset($data['church']) ? trim($data['church']) : '');
$interest = isset($data['ministry_interest']) ? trim($data['ministry_interest']) : (isset($data['ministry']) ? trim($data['ministry']) : '');
$testimony = isset($data['testimony']) ? trim($data['testimony']) : '';

// Validate required fields
$errors = [];
if (empty($full_name)) $errors[] = 'Full name is required';
if (empty($email)) $errors[] = 'Email is required';
if (empty($phone)) $errors[] = 'Phone number is required';
if (empty($gender)) $errors[] = 'Gender is required';
if (empty($church)) $errors[] = 'Church/Fellowship is required';
if (empty($interest)) $errors[] = 'Ministry interest is required';

// Validate email format
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

try {
    // Check if email already exists
    $checkStmt = $pdo->prepare("SELECT id FROM registrations WHERE email_address = ?");
    $checkStmt->execute([$email]);
    
    if ($checkStmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'This email is already registered']);
        exit;
    }
    
    // Insert into database
    $stmt = $pdo->prepare("
        INSERT INTO registrations 
        (full_name, email_address, phone_number, gender, church_fellowship, ministry_interest, testimony) 
        VALUES (:full_name, :email, :phone, :gender, :church, :interest, :testimony)
    ");
    
    $result = $stmt->execute([
        ':full_name' => htmlspecialchars($full_name),
        ':email' => filter_var($email, FILTER_SANITIZE_EMAIL),
        ':phone' => htmlspecialchars($phone),
        ':gender' => htmlspecialchars($gender),
        ':church' => htmlspecialchars($church),
        ':interest' => htmlspecialchars($interest),
        ':testimony' => htmlspecialchars($testimony)
    ]);
    
    if ($result) {
        $lastId = $pdo->lastInsertId();
        
        // Log successful registration
        error_log("Registration successful for: $email, ID: $lastId");
        
        echo json_encode([
            'success' => true, 
            'message' => 'Registration successful! Welcome to WESO.',
            'id' => $lastId
        ]);
    } else {
        throw new Exception('Failed to insert registration');
    }
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Database error. Please try again later.'
    ]);
} catch (Exception $e) {
    error_log("Registration error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Registration failed. Please try again.'
    ]);
}
?>