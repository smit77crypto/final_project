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

// Include database connection
include 'db_connect.php'; // Ensure db_connect.php is correctly configured

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the POST data
    $data = json_decode(file_get_contents('php://input'), true);
    // Get the token and membership_id from POST data
    $token = $data['token'] ?? '';
    $membership_id = (int)($data['membership_id'] ?? 0); // Ensure membership_id is treated as an integer
    $secret_key = 'yo12ur'; // Make sure this is your secret key for JWT
    
    try {
        // Decode the JWT token to get the username
        $decode = JWT::decode($token, new Key($secret_key, 'HS256'));
        $username = $decode->username; // Extract username from decoded token

        // SQL query to update the membership_id for the given username
        $sql = "UPDATE register SET membership_id = ? WHERE username = ?";

        // Prepare the SQL statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters to the prepared statement
            $stmt->bind_param("is", $membership_id, $username); // 'i' for integer, 's' for string (username)

            // Execute the statement
            if ($stmt->execute()) {
                // If the update was successful
                echo json_encode(['status' => 'success', 'message' => 'Membership ID updated successfully']);
            } else {
                // If the query failed
                echo json_encode(['status' => 'error', 'message' => 'Failed to update membership ID: ' . $stmt->error]);
            }

            // Close the prepared statement
            $stmt->close();
        } else {
            // If preparing the query failed
            echo json_encode(['status' => 'error', 'message' => 'Failed to prepare the update query']);
        }

        // Close the database connection
        $conn->close();
        
    } catch (Exception $e) {
        // If the token is invalid or expired
        echo json_encode(['status' => 'error', 'message' => 'Invalid token or error: ' . $e->getMessage()]);
    }
}
?>
