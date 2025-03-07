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

    // Get game_id and slot (date) from JSON data
    $game_id = $data['game_id'];
    $date = $data['date'];
    $slot = $data['slot'];
    $phone = $data['phone_no'];
    if (empty($game_id) || empty($date)) {
        echo json_encode(['message' => 'Fields (game_id and date) are required.']);
    }

    include 'db_connect.php'; // Includes the database connection file

    // Prepare a query to get the game name from the 'games' table using the 'game_id'
    $sql_game_name = "SELECT name FROM games WHERE id = ?";
    $stmt_game_name = $conn->prepare($sql_game_name);
    $stmt_game_name->bind_param("i", $game_id);
    $stmt_game_name->execute();
    $result_game_name = $stmt_game_name->get_result();

    if ($result_game_name->num_rows > 0) {
        $game_row = $result_game_name->fetch_assoc();
        $game_name = $game_row['name']; 
        
    } else {
        echo json_encode(['message' => 'Game not found']);
    }

    // Prepare a query to check if slots for the given game and date already exist in the 'book_game' table
    $sql_check_detail = "SELECT username, email FROM register WHERE phone_no = ?";
    $stmt_check_detail = $conn->prepare($sql_check_detail);
    $stmt_check_detail->bind_param('s', $phone);
    $stmt_check_detail->execute();
    $result = $stmt_check_detail->get_result();

    // Check if the user is found
    if ($user = $result->fetch_assoc()) {
        $username = $user['username'];
        $email = $user['email'];
    } else {
        echo json_encode(['success' => false, 'message' => 'Phone Number is not registred']);
    }

    // Close the statement
    $stmt_check_detail->close();

    //here insert all data 
    $sql_insert = "INSERT INTO book_game (username, email, phone_no, game_name, slot, book_date) 
    VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt_insert = $conn->prepare($sql_insert)) {
    // Bind the parameters to the prepared statement
    $stmt_insert->bind_param("ssssss", $username, $email, $phone, $game_name, $slot, $date);

    // Execute the query
    if ($stmt_insert->execute()) {

    echo json_encode(['success' => true, 'message' => 'Game booked successfully.']);
    } else {
    echo json_encode(['success' => false, 'message' => 'Failed to book game: ' . $stmt_insert->error]);
    }
    $stmt_insert->close();
    } else {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare insert query']);
    }

    // Close the database connection
    $conn->close();
}
?>
