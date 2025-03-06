<?php
include('connect_database.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['game_id'])) {
        $game_id = intval($_POST['game_id']); // Ensure it's an integer

        $query = "SELECT slots FROM games WHERE id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $game_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $slots = explode(",", $row['slots']); // Assuming slots are stored as CSV
                echo json_encode(['slots' => $slots]);
            } else {
                echo json_encode(['error' => 'No slots found']);
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Query preparation failed: ' . $conn->error]);
        }
    } else {
        echo json_encode(['error' => 'Missing game ID']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>