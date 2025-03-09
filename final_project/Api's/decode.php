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
    // Get the token from request body
    $data = json_decode(file_get_contents('php://input'), true);
    $token = $data['token'] ?? '';

    // Check if the token exists
    if (empty($token)) {
        echo json_encode(['success' => false, 'message' => 'Token is missing']);
        exit;
    }

    // Secret key for decoding the token
    $secret_key = 'yo12ur'; 

    try {
        // Decode the JWT token
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        
        // Access the decoded data (e.g., $decoded->username)
        $user_id = $decoded->user_id; // Assuming user_id is stored in the token

        // Fetch fresh data from the database using the user_id
        // Database connection (replace with your actual database connection code)
        include 'db_connect.php'; // Your database connection file

        // Query to get the latest data of the user
        $sql = "SELECT * FROM register WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id); // Bind the user_id from the token to the query
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch fresh data
            $fresh_data = $result->fetch_assoc();
            
            // Send the fresh data along with the token (maintaining the same token)
            echo json_encode(['success' => true, 'data' => $fresh_data, 'token' => $token]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Invalid token']);
    }
}
?>
