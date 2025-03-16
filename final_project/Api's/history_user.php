<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

header('Content-Type: application/json');

require_once '../admin/vendor/autoload.php'; // Ensure this points to the correct autoload file for JWT
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

include 'db_connect.php'; // Ensure db_connect.php is correctly configured

date_default_timezone_set('Asia/Kolkata'); // Set your timezone

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['token'])) {
        echo json_encode(['success' => false, 'message' => 'Token is required']);
        exit;
    }

    $token = $data['token'];
    $secret_key = 'yo12ur';

    try {
        $decode = JWT::decode($token, new Key($secret_key, 'HS256'));
        $username = $decode->username;

        // Fetch data from the book_game table where username matches
        $stmt = $conn->prepare("SELECT * FROM book_game WHERE username = ? and deleted=1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        $currentDate = date('Y-m-d');
        $currentTime = date('H:i');

        $upcoming = [];
        $past = [];

        while ($row = $result->fetch_assoc()) {
            $bookingDate = $row['book_date'];
            $slot = $row['slot'];

            if ($bookingDate < $currentDate) {
                $past[] = $row;
            } elseif ($bookingDate > $currentDate) {
                $upcoming[] = $row;
            } else {
                $startTime = explode('-', $slot)[0];
                $amOrPm = strtoupper(substr($slot, -2));
                $slotTime = $startTime . $amOrPm; 
                $slotTime24Hour = date('H:i', strtotime($slotTime));
                if ($slotTime24Hour < $currentTime) {
                    $past[] = $row;
                } else {
                    $upcoming[] = $row;
                }     
            }
        }
        echo json_encode([
            'success' => true,
            'upcoming' => $upcoming,
            'past' => $past
        ]);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Invalid token or error: ' . $e->getMessage()]);
    }
}

?>
