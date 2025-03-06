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
    
    $token = $data['token'];
    $date = $data['date']; 
    $slot = $data['slot'];
    $game_id = $data['game_id'];
    $secret_key = 'yo12ur'; 

    try {
        // Decode the JWT token to get the username
        $decode = JWT::decode($token, new Key($secret_key, 'HS256'));
        $username = $decode->username; 
        $email = $decode->user_email;
        $phone_no = $decode->user_phone;
      
        // Query to get the game name based on game_id
        $sql_game_name = "SELECT name FROM games WHERE id = ?";
        $stmt_game_name = $conn->prepare($sql_game_name);
        $stmt_game_name->bind_param("i", $game_id);
        $stmt_game_name->execute();
        $result_game_name = $stmt_game_name->get_result();
    
        // Check if the game was found
        if ($result_game_name->num_rows > 0) {
            $game_row = $result_game_name->fetch_assoc();
            $game_name = $game_row['name']; 
            echo $game_name;// Game name fetched from the game table
        } else {
            echo json_encode(['message' => 'Game not found.']);
            http_response_code(404); // Not Found
            exit();
        }

        // Prepare the SQL query for inserting booking data into the book_game table
        $sql_insert = "INSERT INTO book_game (username, email, phone_no, game_name, slot, book_date) 
                       VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt_insert = $conn->prepare($sql_insert)) {
            // Bind the parameters to the prepared statement
            $stmt_insert->bind_param("ssssss", $username, $email, $phone_no, $game_name, $slot, $date);

            // Execute the query
            if ($stmt_insert->execute()) {
                echo json_encode(['success' => true, 'message' => 'Game booked successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to book game: ' . $stmt_insert->error]);
            }

            // Close the statement
            $stmt_insert->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare insert query']);
        }

        // Close the database connection
        $conn->close();

    } catch (Exception $e) {
        // If the token is invalid or expired
        echo json_encode(['success' => false, 'message' => 'Invalid token or error: ' . $e->getMessage()]);
    }
}
?>
