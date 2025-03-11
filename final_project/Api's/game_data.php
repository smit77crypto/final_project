<?php
// Set the appropriate headers for JSON output and CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");  // Explicitly set the response type to JSON

// Database connection details
include 'db_connect.php';

// Query to fetch all game data
$sql = "SELECT * FROM games where deleteval = 1";
$result = $conn->query($sql);

// Initialize an array to store the games
$games = [];

if ($result->num_rows > 0) {
    // Fetch data for each game and store it in the array
    while ($row = $result->fetch_assoc()) {
        $games[] = [
            "id" => $row["id"],
            "name" => $row["name"],
            "half_hour" => $row["half_hour"],
            "hour" => $row["hour"],
            "card_image" => $row["card_image"],
            "slot_image" => $row["slider_image"]
        ];
    }
} else {
    // If no games found, return an empty array
    $games = [];
}

// Close connection
$conn->close();

// Return the game data as JSON
echo json_encode($games);
?>
