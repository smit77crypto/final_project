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
    $servername = "localhost";
    $dbname = "getinplay";
    $dbuser = "root";
    $dbpassword = "root";

    // Create a MySQLi connection
    $conn = new mysqli($servername, $dbuser, $dbpassword, $dbname);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to check if the user exists (using prepared statement)
    $query = "SELECT * FROM register WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username); // 's' indicates the parameter type is string
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    // Check if user is found
    if ($result->num_rows > 0) {
        
        // User exists, fetch the user data
        $user = $result->fetch_assoc();
        $hashpassword = $user['user_password']; // Hashed password stored in DB
        
        // Verify the password using password_verify
        if (password_verify($password, $hashpassword)) {
            // Password matches, successful login
            
            if ($user['deleteval'] === 1) {  // Check if deleteval is 1 (active account)
              
                // JWT Payload (User-specific data to encode in the JWT)
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
                // JWT Secret Key (should be securely stored in environment or config file)
                $secret_key = 'yo12ur';  // Replace this with your actual secret key

                // Generate the JWT token
                $jwt = JWT::encode($payload, $secret_key,'HS256');
                // Send the JWT as a cookie (optional: Secure, HttpOnly for better security)
                setcookie("auth_token", $jwt, [
                    'expires' => time() + 3600,
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'None'
                ]);// 1-hour expiration

                // Respond with the success message and JWT token
                echo json_encode([
                    'success' => true,
                    'message' => 'Login successful'
                ]);
            } else {
                // Account is deactivated (deleteval != 1)
                echo json_encode([
                    'success' => false,
                    'message' => 'Your account has been removed by the admin. Please contact admin.'
                ]);
            }
        } else {
            // Password does not match
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

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>