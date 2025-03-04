<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Set up the header for the response
header('Content-Type: application/json');

// Include the JWT library (use Composer autoload or manually include the library)
require_once 'vendor/autoload.php'; // Ensure this points to the correct autoload file for JWT
// use \Firebase\JWT\JWT;
echo "hello";
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
        $hashpassword =  $user['user_password'];
        // Verify the password using password_verify
        if (password_verify($user_password, $hashpassword)) {
            // Password matches, successful login
            if ($user['deleteval'] === 1) {
              
                // JWT Payload
                $payload = [
                    'iat' => time(),  // Issued at time
                    'exp' => time() + 3600,  // Expiration time (1 hour from now)
                    'username' => $user['username'],
                    'user_id' => $user['id'],
                    'user_phone' => $user['phone_no'],
                    'user_email' => $user['email'],
                    'full_name' => $user['full_name'],
                    'membership_id' => $user['membership_id']
                    // Add any other data you'd like to store in the token
                ];

                // JWT Secret Key (should be securely stored in environment or config file)
                $secret_key = "your_secret_key_here";

                // Generate the JWT token
                $jwt = JWT::encode($payload, $secret_key);

                // Optional: Store the JWT in a database column (you can save it to a new `auth_token` column if needed)
                // $update_query = "UPDATE register SET auth_token = ? WHERE id = ?";
                // $stmt_update = $conn->prepare($update_query);
                // $stmt_update->bind_param('si', $jwt, $user['id']);
                // $stmt_update->execute();
                // $stmt_update->close();

                // Send the JWT as a cookie (Optional: add secure and HttpOnly for better security)
                setcookie("auth_token", $jwt, time() + 3600, "/", "", false, true);  // 1 hour expiration, Secure, HttpOnly

                echo json_encode(['success' => true, 'message' => 'Login successful', 'token' => $jwt]);

            } else {
                echo json_encode(['success' => false, 'message' => 'Your account has been removed by admin. Please contact admin.']);
            }
        } else {
            // Password does not match
            echo json_encode(['success' => false, 'message' => 'Incorrect password']);
        }
    } else {
        // No user found
        echo json_encode(['success' => false, 'message' => 'No user found']);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
