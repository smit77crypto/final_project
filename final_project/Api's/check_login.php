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
    $username = $data['username'] ?? '';
    $user_password = $data['password'] ?? '';

    // Check if username and password are provided
    if (empty($username) || empty($user_password)) {
        echo json_encode(['message' => 'Username and password are required.']);
        http_response_code(400); // Bad Request
        exit();
    }

    // Trim whitespace from the password (if any)
    $password = trim($user_password);

    // Connect to MySQL Database
    include 'db_connect.php';
    // Query to check if the user exists (using prepared statement)
    $query = "SELECT * FROM register WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username); // 's' indicates the parameter type is string
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $hashpassword = $user['user_password']; 
        if (password_verify($password, $hashpassword)) {
            if ($user['deleteval'] === 1) {  // Check if deleteval is 1 (active account)
                $payload = [
                    'username' => $user['username'],
                    'user_id' => $user['id'],
                    'user_phone' => $user['phone_no'],
                    'user_email' => $user['email'],
                    'full_name' => $user['full_name'],
                    'membership_id' => $user['membership_id'],
                    'iat' => time(), // Issued at time
                    'exp' => time() + 3600 // Expiration time (1 hour from now)
                ];
                $secret_key = 'yo12ur'; 
                // Generate the JWT token
                $jwt = JWT::encode($payload, $secret_key,'HS256');
                setcookie("auth_token", $jwt, time() + 3600, "/", "", false, true);  
                echo json_encode([
                    'success' => true,
                    'message' => 'Login successful',
                    'token' => $jwt
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Your account has been removed by the admin. Please contact admin.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Incorrect password'
            ]);
        }
    } else {
        // No user found
        echo json_encode([
            'success' => false,
            'message' => 'No user found'
        ]);
    }
    $stmt->close();
    $conn->close();
}
?>
