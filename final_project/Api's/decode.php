<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

header('Content-Type: application/json');

// Include the JWT library (use Composer autoload or manually include the library)
require_once '../admin/vendor/autoload.php'; // Ensure this points to the correct autoload file for JWT
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the token from cookies
    $data = json_decode(file_get_contents('php://input'), true);
    // Get username and password from JSON data
    $token = $data['token'] ?? '';
    // Check if the token exists
    if (empty($token)) {
        echo json_encode(['sucess'=>false]);
    }else{
        $secret_key = yo12ur; 
        // Decode the JWT token
        $decode = JWT::decode($token, new Key($secret_key, 'HS256'));
        // You can access the decoded data as an object (e.g., $decode->username)
        echo json_encode(['success'=>true, 'data' => $decode]);
    }  
}
?>
