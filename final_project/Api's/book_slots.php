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
    $game_id = $data['game_id'] ?? '';
    $date = $data['date'] ?? ''; // Renaming 'slot' to 'date' based on your request

    // Check if necessary data is provided
    if (empty($game_id) || empty($date)) {
        echo json_encode(['message' => 'Fields (game_id and date) are required.']);
        http_response_code(400); // Bad Request
        exit();
    }

    include 'db_connect.php'; // Includes the database connection file

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

    // Prepare a query to check if slots for the given game and date already exist in the 'book_game' table
    $sql_check_slot = "SELECT username,phone_no,email,slot FROM book_game WHERE game_name = ? AND book_date = ?";
    $stmt_check_slot = $conn->prepare($sql_check_slot);
    $stmt_check_slot->bind_param("ss", $game_name, $date);
    $stmt_check_slot->execute();
    $result_check_slot = $stmt_check_slot->get_result();

    $booked_slots = [];
    
    // Fetch all the booked slots and store them in the $booked_slots array
    while ($row = $result_check_slot->fetch_assoc()) {
        $booked_slots[] = [
            'username' => $row['username'],
            'phone_no' => $row['phone_no'],
            'email' => $row['email'],
            'slot' => $row['slot']
        ];
    }
    

    if (empty($booked_slots)) {
        echo json_encode(['message' => 'No slots booked for this game on the selected date.', 'booked_slots' => []]);
        http_response_code(200); // OK, but no slots booked
    } else {
        echo json_encode(['message' => 'Slots already booked for this game on the selected date.', 'booked_slots' => $booked_slots]);
        http_response_code(200); // OK, slots are booked
    }

    // Close the prepared statements and database connection
    $stmt_game_name->close();
    $stmt_check_slot->close();
    $conn->close();
}
?>
