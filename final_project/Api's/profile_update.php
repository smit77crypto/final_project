<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

header('Content-Type: application/json');

require_once '../admin/vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $token = $data['token'];
    $name = $data['name'];
    $email = $data['email'];
    $phone_no = $data['phone_no'];

    // Validate input
    if (empty($token)) {
        echo json_encode(['message' => 'Token is required.']);
        http_response_code(400); 
        exit();
    }
    if (empty($name) || empty($email) || empty($phone_no)) {
        echo json_encode(['message' => 'Name, email, and phone number are required.']);
        http_response_code(400); 
        exit();
    }
    
    include 'db_connect.php';  

    $secret_key = 'yo12ur'; 

    try {
        // Decode the JWT token to extract the username
        $decode = JWT::decode($token, new Key($secret_key, 'HS256'));
        $username_a = $decode->username;  
        $stmt = $conn->prepare("UPDATE register SET full_name = ?, email = ?, phone_no = ? WHERE username = ?");
        $stmt->bind_param("ssss", $name, $email, $phone_no, $username_a);  
        if ($stmt->execute()) {
    
            if ($stmt->affected_rows > 0) {
                echo json_encode(['success'=>true , 'message' => 'Profile updated successfully.']);
            } else {
                echo json_encode(['success'=>false , 'message' => 'Failed to update the profile. No changes were made. Please ensure the data is correct.']);
            }
        } else {
            echo json_encode(['success'=>false , 'message' => 'Failed to update user details.']); 
        }
        
        $stmt->close();
    } catch (Exception $e) {
     
        echo json_encode(['message' => 'Invalid token or error: ' . $e->getMessage()]);
        
    } finally {
 
        $conn->close();
    }
}
?>
