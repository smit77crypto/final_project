<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Set up the header for the response
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the raw POST data (JSON format)
    $data = json_decode(file_get_contents('php://input'), true);

    // Get username, phone, email, game_id, and slot from JSON data
    $username = $data['username'] ?? '';
    $phone = $data['phone'] ?? '';
    $email = $data['email'] ?? '';
    $game_id = $data['game_id'] ?? '';
    $slot = $data['slot'] ?? '';

    // Check if necessary data is provided
    if (empty($username) || empty($phone) || empty($email) || empty($game_id) || empty($slot)) {
        echo json_encode(['message' => 'All fields (username, phone, email, game_id, and slot) are required.']);
        http_response_code(400); // Bad Request
        exit();
    }

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

    // Prepare a query to get the game name from the 'games' table using the 'game_id'
    $sql_game_name = "SELECT name FROM games WHERE id = ?";
    $stmt_game_name = $conn->prepare($sql_game_name);
    $stmt_game_name->bind_param("i", $game_id);
    $stmt_game_name->execute();
    $result_game_name = $stmt_game_name->get_result();

    // Check if the game was found
    if ($result_game_name->num_rows > 0) {
        $game_row = $result_game_name->fetch_assoc();
        $game_name = $game_row['name']; // Game name fetched from the game table
    } else {
        echo json_encode(['message' => 'Game not found.']);
        http_response_code(404); // Not Found
        exit();
    }

    // Prepare a query to insert data into the 'book_game' table (No game_id needed)
    $sql_insert = "INSERT INTO book_game (username, phone_no, email, game_name, slot) 
                   VALUES (?, ?, ?, ?, ?)";

    $stmt_insert = $conn->prepare($sql_insert);

    // Assuming 'slot' is an integer. If it's a string, change the 'i' to 's' in bind_param.
    $stmt_insert->bind_param("sssss", $username, $phone, $email, $game_name, $slot);

    // Execute the query to insert the data
    if ($stmt_insert->execute()) {
        echo json_encode(['success' => true ,'message' => 'Booking successful.']);
        http_response_code(200); // OK
    } else {
        // Log error for debugging purposes
        error_log("Error: " . $stmt_insert->error);
        echo json_encode(['success' => false ,'message' => 'Failed to insert data.']);
        http_response_code(500); // Internal Server Error
    }

    // Close the prepared statements and database connection
    $stmt_game_name->close();
    $stmt_insert->close();
    $conn->close();
}
?>
