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

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }

        echo json_encode([
            'success' => true,
            'bookings' => $bookings
        ]);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Invalid token or error: ' . $e->getMessage()]);
    }
}
?>
