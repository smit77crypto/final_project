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

        // Query to fetch current membership_id from the database
        $sql = "SELECT membership_id FROM register WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username); // 's' indicates the parameter type is string (username)
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            // Check if the current membership_id is the same as the provided one
            if ($user['membership_id'] == $membership_id) {
                // Membership is already updated, send a response indicating so
                echo json_encode(['success' => true, 'message' => 'Membership already updated by admin']);
            } else {
                // Membership is not updated yet, so proceed with the update
                $sql_update = "UPDATE register SET membership_id = ? WHERE username = ?";
                if ($update_stmt = $conn->prepare($sql_update)) {
                    // Bind the parameters for the update query
                    $update_stmt->bind_param("is", $membership_id, $username); // 'i' for integer, 's' for string (username)

                    // Execute the update query
                    if ($update_stmt->execute()) {
                        // Query to select the user by username to fetch fresh data
                        $query = "SELECT * FROM register WHERE username = ? LIMIT 1";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param('s', $username); // 's' indicates the parameter type is string
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($user = $result->fetch_assoc()) {
                            // Prepare the payload with the updated user data
                            $payload = [
                                'username' => $user['username'],
                                'user_id' => $user['id'],
                                'user_phone' => $user['phone_no'],
                                'user_email' => $user['email'],
                                'full_name' => $user['full_name'],
                                'membership_id' => $user['membership_id']
                            ];
                            // Secret key for encoding the JWT
                            $jwt = JWT::encode($payload, $secret_key, 'HS256');

                            // Return success message with the updated token
                            echo json_encode(['success' => true, 'message' => 'Membership updated successfully', 'token' => $jwt]);
                        } else {
                            echo json_encode(['success' => false, 'message' => 'User not found']);
                        }
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Failed to update membership ID: ' . $update_stmt->error]);
                    }
                    // Close the update statement
                    $update_stmt->close();
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to prepare the update query']);
                }
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }

        // Close the prepared statement
        $stmt->close();
        // Close the database connection
        $conn->close();

    } catch (Exception $e) {
        // If the token is invalid or expired
        echo json_encode(['success' => false, 'message' => 'Invalid token or error: ' . $e->getMessage()]);
    }
}
?>
