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

    // Get game_id, date, slot, and phone_no from JSON data
    $game_id = $data['game_id'] ?? null;
    $date = $data['date'] ?? null;
    $slot = $data['slot'] ?? null;
    $phone = $data['phone_no'] ?? null;

    // Validate required fields
    if (empty($game_id) || empty($date) || empty($slot)) {
        echo json_encode(['success' => false, 'message' => 'Fields (game_id, date, and slot) are required.']);
        exit(); // Exit early to prevent further processing
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
        echo json_encode(['success' => false, 'message' => 'Game not found']);
        exit();
    }

    // Prepare a query to check if the phone number exists in the 'register' table
    $sql_check_user = "SELECT username, email, membership_id FROM register WHERE phone_no = ?";
    $stmt_check_user = $conn->prepare($sql_check_user);
    $stmt_check_user->bind_param('s', $phone);
    $stmt_check_user->execute();
    $result_user = $stmt_check_user->get_result();

    // Check if the user is found
    if ($user = $result_user->fetch_assoc()) {
        $username = $user['username'];
        $email = $user['email'];
        $membership_id = $user['membership_id'];  
    } else {
        echo json_encode(['success' => false, 'message' => 'Phone number is not registered']);
        exit();
    } 

    // Close the user check query
    $stmt_check_user->close();

    // Convert the date to YYYY-MM-DD format if it is in YYYY/MM/DD format
    $formattedDate = DateTime::createFromFormat('Y/m/d', $date);
    if ($formattedDate === false) {
        echo json_encode(['success' => false, 'message' => 'Invalid date format. Expected format: YYYY/MM/DD']);
        exit();
    }
    $formattedDate = $formattedDate->format('Y-m-d'); // Format to YYYY-MM-DD for database comparison

    // Logic to check if the booking date is allowed based on membership_id
    $currentDate = new DateTime(); // Today's date
    $currentDateString = $currentDate->format('Y-m-d'); // Format the current date as YYYY-MM-DD

    $allowedBookingDate = false; // Default is false, until validated

    switch ($membership_id) {
        case 1:
            // Membership 1: Can book only today
            if ($formattedDate === $currentDateString) {
                $allowedBookingDate = true;
            } else {
                echo json_encode(['success' => false, 'message' => 'You can only book for today.']);
                exit();
            }
            break;
        case 2:
            // Membership 2: Can book today and tomorrow
            $tomorrow = $currentDate->modify('+1 day')->format('Y-m-d');
            if ($formattedDate === $currentDateString || $formattedDate === $tomorrow) {
                $allowedBookingDate = true;
            } else {
                echo json_encode(['success' => false, 'message' => 'You can only book for today or tomorrow.']);
                exit();
            }
            break;
        case 3:
            // Membership 3: Can book any day
            $allowedBookingDate = true; // No date restrictions
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid membership ID']);
            exit();
    }

    if (!$allowedBookingDate) {
        echo json_encode(['success' => false, 'message' => 'Booking date is not allowed based on your membership.']);
        exit();
    }

    // Check if the slot is already booked for the given game and date
    $sql_check_slot = "SELECT id FROM book_game WHERE game_name = ? AND book_date = ? AND slot = ?";
    $stmt_check_slot = $conn->prepare($sql_check_slot);
    $stmt_check_slot->bind_param('sss', $game_name, $formattedDate, $slot);
    $stmt_check_slot->execute();
    $result_check_slot = $stmt_check_slot->get_result();

    if ($result_check_slot->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Slot already booked for this game and date. Refresh your page']);
        exit();
    }

    // Prepare the SQL query for inserting booking data into the book_game table
    $sql_insert = "INSERT INTO book_game (username, email, phone_no, game_name, slot, book_date) 
                   VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt_insert = $conn->prepare($sql_insert)) {
        // Bind the parameters to the prepared statement
        $stmt_insert->bind_param("ssssss", $username, $email, $phone, $game_name, $slot, $formattedDate);

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
