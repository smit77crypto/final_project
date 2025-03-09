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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function sendEmail( $name, $email, $phone_no,$game_name, $slot, $date, $submission_time) {
    $smtp_pw = trim(file_get_contents('my.txt'));

    $mail = new PHPMailer(true);
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Gmail SMTP Server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sohan11903@gmail.com'; // Your Gmail
        $mail->Password   = $smtp_pw; // Use Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL
        $mail->Port       = 465;

        // Sender and recipient
        $mail->setFrom('sohan11903@gmail.com', 'Sohan');
        $mail->addAddress($email, $name);
        $mail->addReplyTo('sohan11903@gmail.com', 'Sohan');

        // Email content
        $mail->isHTML(true);
        $mail->Subject =  "Slot Booking Details " . htmlspecialchars($name);
        $mail->Body = '<h3>Hello ' . htmlspecialchars($name) . ',</h3>
            <p>message from Admin</p>
            <ul>
                <li><b>Name:</b> ' . htmlspecialchars($name) . '</li>
                <li><b>Email:</b> ' . htmlspecialchars($email) . '</li>
                <li><b>Phone No:</b> ' . htmlspecialchars($phone_no) . '</li>
                <li><b>Message Send At:</b> ' . htmlspecialchars($submission_time) . '</li>
            </ul>
            <h4>Slot Detail:-</h4>
            <p>Game Name :-'. htmlspecialchars($game_name).'</p>
            <p>time :-'. htmlspecialchars($slot).'</p>
            <p>Date :-'. htmlspecialchars($date).'</p>
            <p>Best Regards,<br> '. htmlspecialchars($name) .' </p>';
        $mail->AltBody = '';

        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Error message to debug email sending issues
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}
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
        $name = $decode->full_name;
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
        } else {
            echo json_encode(['message' => 'Game not found.']);
            http_response_code(404); // Not Found
            exit();
        }

        // Query to check if the slot already exists in the book_game table
        $sql_check_slot = "SELECT id FROM book_game WHERE game_name = ? AND book_date = ? AND slot = ?";
        $stmt_check_slot = $conn->prepare($sql_check_slot);
        $stmt_check_slot->bind_param("sss", $game_name, $date, $slot);
        $stmt_check_slot->execute();
        $result_check_slot = $stmt_check_slot->get_result();

        // Check if the slot is already booked
        if ($result_check_slot->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Slot already booked. Please refresh your page.']);
            http_response_code(200); // Conflict
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
                $submission_time = date('Y-m-d H:i:s');
                sendEmail($name, $email, $phone_no, $game_name, $slot, $date, $submission_time);
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

    } catch (Exception $e) {
        // If the token is invalid or expired
        echo json_encode(['success' => false, 'message' => 'Invalid token or error: ' . $e->getMessage()]);
    }
}
?>
