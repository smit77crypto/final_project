<?php
// Set the appropriate headers for JSON output and CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");  // Explicitly set the response type to JSON

// Get the raw POST data (for GET request with JSON body)

    // Database connection details
    include 'db_connect.php';
    $id=1;
    // Query to fetch game data based on the provided id
    $sql = "SELECT content FROM terms WHERE id = $id";
    $result = $conn->query($sql);

    // Initialize an array to store the response data
    $response = [];

    if ($result->num_rows > 0) {
        // Fetch the game data
        $row = $result->fetch_assoc();
        $response = $row["content"];
    } else {
        // If no game found with the given id
        $response = ["info" => "terms not set yet"];
    }

    // Close the connection
    $conn->close();

    // Return the game data as JSON
    echo json_encode($response, JSON_PRETTY_PRINT);

?>
