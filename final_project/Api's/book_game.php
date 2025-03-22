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


function sendEmail($name, $email, $phone_no, $game_name, $slot, $date, $price) {
    $smtp_pw = trim(file_get_contents('my.txt'));

    $mail = new PHPMailer(true);
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Gmail SMTP Server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'getinplay.contact@gmail.com'; // Your Gmail
        $mail->Password   = $smtp_pw; // Use Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL
        $mail->Port       = 465;

        // Sender and recipient
        $mail->setFrom('getinplay.contact@gmail.com', 'GetInPlay');
        $mail->addAddress($email, $name);
        $mail->addReplyTo('getinplay.contact@gmail.com', 'GetInPlay');

        // Email content
        $mail->isHTML(true);
        $mail->Subject =  "Slot Booking Details " . htmlspecialchars($name);
        $mail->Body = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slot Booking Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f9f9f9; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 20px auto; padding: 20px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
        <div style="font-size: 24px; font-weight: bold; color: #2c3e50; margin-bottom: 20px;">Hello ' . htmlspecialchars($name) . ',</div>
        <div style="font-size: 16px; color: #555;">
            <p>Your slot booking details are confirmed. Below are the details:</p>
            <div style="margin: 20px 0; padding: 15px; background-color: #f1f1f1; border-radius: 6px;">
                <ul style="padding: 0; margin: 0;">
                    <li style="list-style: none; margin-bottom: 10px;"><b style="color: #2c3e50;">Name:</b> ' . htmlspecialchars($name) . '</li>
                    <li style="list-style: none; margin-bottom: 10px;"><b style="color: #2c3e50;">Email:</b> ' . htmlspecialchars($email) . '</li>
                    <li style="list-style: none; margin-bottom: 10px;"><b style="color: #2c3e50;">Phone No:</b> ' . htmlspecialchars($phone_no) . '</li>
                </ul>
            </div>
            <h4 style="font-size: 20px; color: #2c3e50; margin-bottom: 10px;">Slot Details:</h4>
            <div style="margin: 20px 0; padding: 15px; background-color: #f1f1f1; border-radius: 6px;">
                <ul style="padding: 0; margin: 0;">
                    <li style="list-style: none; margin-bottom: 10px;"><b style="color: #2c3e50;">Game Name:</b> ' . htmlspecialchars($game_name) . '</li>
                    <li style="list-style: none; margin-bottom: 10px;"><b style="color: #2c3e50;">Time:</b> ' . htmlspecialchars($slot) . '</li>
                    <li style="list-style: none; margin-bottom: 10px;"><b style="color: #2c3e50;">Date:</b> ' . htmlspecialchars($date) . '</li>
                    <li style="list-style: none; margin-bottom: 10px;"><b style="color: #2c3e50;">Price:</b> ₹'. htmlspecialchars($price) .'</li>
                </ul>
            </div>
            <p>Thank you for booking with us. We look forward to seeing you!</p>
        </div>
        <div style="margin-top: 20px; font-size: 14px; color: #777; ">
            <p>Best Regards,<br>' . htmlspecialchars($name) . '</p>
            <p style="text-align:center">If you have any questions, feel free to <a href="mailto:getinplay.contact@gmail.com" style="color: #3498db; text-decoration: none;">contact us</a>.</p>
        </div>
    </div>
</body>
</html>
';
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
    $price = $data['price'];
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
        $sql_check_slot = "SELECT id FROM book_game WHERE game_id = ? AND book_date = ? AND slot = ? AND deleted = 1";
        $stmt_check_slot = $conn->prepare($sql_check_slot);
        $stmt_check_slot->bind_param("iss", $game_id, $date, $slot);
        $stmt_check_slot->execute();
        $result_check_slot = $stmt_check_slot->get_result();

        // Check if the slot is already booked
        if ($result_check_slot->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Slot already booked. Please refresh your page.']);
            http_response_code(200); // Conflict
            exit();
        }
        
        // Prepare the SQL query for inserting booking data into the book_game table
        $sql_insert = "INSERT INTO book_game (username, email, phone_no, game_id, slot,price, book_date) 
                       VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt_insert = $conn->prepare($sql_insert)) {
            // Bind the parameters to the prepared statement
            $stmt_insert->bind_param("sssisds", $username, $email, $phone_no, $game_id, $slot, $price, $date);

            // Execute the query
            if ($stmt_insert->execute()) {
                sendEmail($name, $email, $phone_no, $game_name, $slot, $date, $price);
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
