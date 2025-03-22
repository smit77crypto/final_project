<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require_once '../admin/vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Handle preflight request
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $token = $data['token'] ?? '';
    $old_password = $data['old_password'] ?? '';
    $new_password = $data['new_password'] ?? '';

    if (empty($token)) {
        http_response_code(400); 
        echo json_encode(['success' => false, 'message' => 'Token is required.']);
        exit();
    }

    include 'db_connect.php'; 
    $secret_key = 'yo12ur';

    try {
        $decode = JWT::decode($token, new Key($secret_key, 'HS256'));
        $username_a = $decode->username;

        $stmt = $conn->prepare("SELECT user_password FROM register WHERE username = ?");
        $stmt->bind_param("s", $username_a);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['user_password'];
            
            if (password_verify($old_password, $hashed_password)) {
                if ($old_password === $new_password) {
                    echo json_encode(['success' => false, 'message' => 'Old password and new password cannot be the same.']);
                    exit();
                }
                
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                $update_stmt = $conn->prepare("UPDATE register SET user_password = ? WHERE username = ?");
                $update_stmt->bind_param("ss", $new_hashed_password, $username_a);
                $update_stmt->execute();

                if ($update_stmt->affected_rows > 0) {
                    echo json_encode(['success' => true, 'message' => 'Password updated successfully.']);
                } else {
                   
                    echo json_encode(['success' => false, 'message' => 'Failed to update the password.']);
                }

                $update_stmt->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Old password is incorrect.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found.']);
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Invalid token or error: ' . $e->getMessage()]);
    }
}
?>
