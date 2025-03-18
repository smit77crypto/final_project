<?php
include('connect_database.php');

if (isset($_POST['game_id'])) {
    $gameId = (int)$_POST['game_id'];
    $query = "SELECT slots FROM games WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $gameId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $slots = explode(',', $row['slots']);
        echo json_encode(['slots' => $slots]);
    } else {
        echo json_encode(['error' => 'No slots found for this game.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request.']);
}
?>