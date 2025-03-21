<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

header('Content-Type: application/json');
require_once '../admin/vendor/autoload.php'; // Ensure this is the correct path
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    $token= $data['token'];
    $user_id = $data['id'];
    $old_password = $data['old_password'];
    $new_password = $data['new_password'];
    
    if (empty($token)) {
        echo json_encode(['message' => 'Token is required.']);
        http_response_code(400); 
        exit();
    }
    
    include 'db_connect.php'; 

    $secret_key = 'yo12ur'; 

    try {
       
        $decode = JWT::decode($token, new Key($secret_key, 'HS256'));
        $username_a = $decode->username;

        $stmt = $conn->prepare("SELECT user_password FROM register WHERE username = ?");
        $stmt->bind_param("s", $username_a); // Bind the username and id to the query
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Check if the user exists
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['user_password'];
            
            // Verify if the old password matches the hashed password in the database
            if (password_verify($old_password, $hashed_password)) {
                if($old_password === $new_password){
                    echo json_encode(['success' => false , 'message' => 'Old password and new password cannot be the same.']);
                    exit();
                }
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database
                $update_stmt = $conn->prepare("UPDATE register SET user_password = ? WHERE username = ?");
                $update_stmt->bind_param("ss", $new_hashed_password, $username_a);
                $update_stmt->execute();

                // Check if the update was successful
                if ($update_stmt->affected_rows > 0) {
                    echo json_encode(['success' => true , 'message' => 'Password updated successfully.']);
                } else {
                    echo json_encode(['success' => false , 'message' => 'Failed to update the password.']);
                    http_response_code(500);
                }

                // Close update statement
                $update_stmt->close();
            } else {
                echo json_encode(['success' => false , 'message' => 'Old password is incorrect.']);
                http_response_code(400);
            }
        } else {
            echo json_encode(['message' => 'User not found.']);
            http_response_code(404);
        }

        // Close the statement and database connection
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        // Handle errors if JWT decoding fails
        echo json_encode(['message' => 'Invalid token or error: ' . $e->getMessage()]);
        http_response_code(401); // Unauthorized
    }
}
?>
