<?php
// Set the appropriate headers for JSON output and CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");  // Explicitly set the response type to JSON

// Get the raw POST data (for GET request with JSON body)
$data = json_decode(file_get_contents("php://input"), true);

// Check if the "id" parameter is provided in the request
if (isset($data['id'])) {
    $game_id = $data['id'];

    // Database connection details
    include 'db_connect.php';

    // Query to fetch game data based on the provided id
    $sql = "SELECT * FROM games WHERE id = $game_id and deleteval=1";
    $result = $conn->query($sql);

    // Initialize an array to store the response data
    $response = [];

    if ($result->num_rows > 0) {
        // Fetch the game data
        $row = $result->fetch_assoc();
        $response = [
            "id" => $row["id"],
            "name" => $row["name"],
            "slots" => explode(",", $row["slots"]),
            "filter"=> explode(",", $row["filter_value"]) // Split the slots string into an array
        ];
    } else {
        // If no game found with the given id
        $response = ["error" => "Game not found"];
    }

    // Close the connection
    $conn->close();

    // Return the game data as JSON
    echo json_encode($response);
} else {
    // If id is not provided in the request
    echo json_encode(["error" => "No id provided"]);
}
?>
