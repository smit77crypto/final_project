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

    if (empty($token)) {
        echo json_encode(['success' => false, 'message' => 'Token is missing']);
        exit;
    }

    $secret_key = 'yo12ur';

    try {
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        $user_id = $decoded->user_id;
        include 'db_connect.php';

        $sql = "SELECT * FROM register WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $fresh_data = $result->fetch_assoc();
            echo json_encode(['success' => true, 'data' => $fresh_data, 'token' => $token]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Invalid token']);
    }
}
?>
