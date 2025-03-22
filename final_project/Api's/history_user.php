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
        
        // Modify the query to JOIN book_game with games table to get the game_name
        $stmt = $conn->prepare("
            SELECT bg.*, g.name 
            FROM book_game bg
            JOIN games g ON bg.game_id = g.id
            WHERE bg.username = ? AND bg.DELETED = 1
        ");
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

            // Add the game name to the response
            // $row['name'] = $row['name'];

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

        // Fetch data from the book_game table where username matches and deleted = 0
        $stmtDeleted = $conn->prepare("
            SELECT bg.*, g.name 
            FROM book_game bg
            JOIN games g ON bg.game_id = g.id
            WHERE bg.username = ? AND bg.DELETED = 0
        ");
        $stmtDeleted->bind_param("s", $username);
        $stmtDeleted->execute();
        $resultDeleted = $stmtDeleted->get_result();

        $deleted = [];
        while ($rowDeleted = $resultDeleted->fetch_assoc()) {
            // $rowDeleted['name'] = $rowDeleted['name']; // Include the game_name for deleted bookings
            $deleted[] = $rowDeleted;
        }

        // Count the number of past, upcoming, and deleted bookings
        $pastCount = count($past);
        $upcomingCount = count($upcoming);
        $deleteCount = count($deleted);

        echo json_encode([
            'success' => true,
            'upcoming' => $upcoming,
            'upcomingCount' => $upcomingCount,
            'past' => $past,
            'pastCount' => $pastCount,
            'deleted' => $deleted,
            'deleteCount' => $deleteCount
        ]);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Invalid token or error: ' . $e->getMessage()]);
    }
}

?>
