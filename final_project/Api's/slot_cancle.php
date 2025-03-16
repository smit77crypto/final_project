<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

header('Content-Type: application/json');

require_once '../admin/vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $token = $data['token'] ?? '';
    $auth = $data['auth'];
    $phone_no = $data['phone_no'];
    $date = $data['date'];
    $slot = $data['slot'];

    include 'db_connect.php';

    if ($auth === 'user') {
        if (empty($token)) {
            echo json_encode(['success' => false, 'message' => 'Token is missing']);
            exit;
        }
        $secret_key = 'yo12ur';
        try {
            $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
            $phone_no = $decoded->user_phone_no;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Invalid token']);
            exit;
        }
    }

    $stmt = $conn->prepare("SELECT book_time FROM book_game WHERE phone_no = ? AND date = ? AND slot = ?");
    $stmt->bind_param('sss', $phone_no, $date, $slot);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book_time = $result->fetch_assoc()['book_time'];

        $stmt = $conn->prepare("SELECT membership_id FROM register WHERE phone_no = ?");
        $stmt->bind_param('s', $phone_no);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $membership_id = $result->fetch_assoc()['membership_id'];
            $allowed_hours = ($membership_id == 1) ? 4 : (($membership_id == 2) ? 3 : 2);
            
            $slot_start_time = explode('-', $slot)[0];
            $amOrPm = strtoupper(substr($slot, -2));
            $slot_start_time_ap = $slot_start_time . $amOrPm;
            $slot_start_time_24hr = date('H:i', strtotime(trim($slot_start_time_ap)));

            $current_time_obj = new DateTime();
            $book_time_obj = new DateTime($date . ' ' . $slot_start_time_24hr);
            $interval = $current_time_obj->diff($book_time_obj);
            $hours_diff = (int)$interval->format('%h') + ($interval->days * 24);

            if ($hours_diff >= $allowed_hours) {
                $stmt = $conn->prepare("UPDATE book_game SET deleted = 0 WHERE phone_no = ? AND date = ? AND slot = ?");
                $stmt->bind_param('sss', $phone_no, $date, $slot);
                $stmt->execute();

                echo json_encode(['success' => true, 'message' => 'Booking cancelled successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Cannot cancel. Allowed cancellation time exceeded.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Membership not found.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Booking not found.']);
    }

    $stmt->close();
    $conn->close();
}
?>
