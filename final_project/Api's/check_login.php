<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Set up the header for the response
header('Content-Type: application/json');

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
    $password = trim($password);

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
    // Check if user is foundaa
    if ($result->num_rows > 0) {
        
        // User exists, fetch the user data
        $user = $result->fetch_assoc();
        $hashpassword =  $user['user_password'];
        // Verify the password using password_verify
        if (password_verify($user_password,$hashpassword)){
            // Password matches, successful login
            if($user['deleteval']===1){
                echo json_encode(['success' => true ,'message' => 'Login successful']);
            }
            else{
                echo json_encode(['success' => false ,'message' => 'your id has been removed by admin please contact to admin']);
            }
        } else {
            // Password does not match
            echo json_encode(['success' => false ,'message' => 'Incorrect password']); 
        }
    } else {
        // No user found
        echo json_encode(['success' => false ,'message' => 'No user found']);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
