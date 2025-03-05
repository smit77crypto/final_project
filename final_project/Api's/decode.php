<?php
// Include required headers for CORS and response format
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

// Check if the origin is set and set the allowed origin dynamically
if (!empty($origin)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    // Default fallback if no origin is provided (although this should not happen in most cases)
    header("Access-Control-Allow-Origin: *");
}

// Allow credentials (cookies, HTTP authentication)
header("Access-Control-Allow-Credentials: true");
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
   
    // Get the token from cookies
    $token = $_COOKIE['auth_token'];
    // Check if the token exists
    if (empty($token)) {
        echo json_encode(['message' => 'Token is required.']);
        http_response_code(400); // Bad Request
        exit();
    }

    // Secret key for JWT decoding
    $secret_key = 'yo12ur'; 

    try {
        // Decode the JWT token
        $decode = JWT::decode($token, new Key($secret_key, 'HS256'));

        // You can access the decoded data as an object (e.g., $decode->username)
        echo json_encode(['message' => 'success', 'data' => $decode]);
    } catch (Exception $e) {
        echo json_encode(['message' => 'Failed to decode token', 'error' => $e->getMessage()]);
        http_response_code(401); // Unauthorized
    }
}
?>
