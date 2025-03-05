<?php
// Include required headers for CORS and response format
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Set content-type for the response
header('Content-Type: application/json');

// Include the JWT library (use Composer autoload or manually include the library)
require_once '../admin/vendor/autoload.php'; // Ensure this points to the correct autoload file for JWT
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get the raw POST data (JSON format)
    $data = json_decode(file_get_contents('php://input'), true);

    // Get username and password from JSON data
    $token = $data['token'] ?? '';

    // Check if username and password are provided
    if (empty($token)) {
        echo json_encode(['message' => 'Token are required.']);
        http_response_code(400); // Bad Request
        exit();
    }
    $secret_key = 'yo12ur'; 
    // Trim whitespace from the password (if any)
    $token = trim($token);

    // Connect to MySQL Database
    

    $decode = JWT::decode($token, new Key($secret_key,'HS256'));
    echo $data->username;   
    echo json_encode(['message' => 'success', 'data' => $decode]);
    $stmt->close();
    $conn->close();
}
?>
