<?php
// check_username.php
header('Content-Type: application/json');

// Simulate a connection to your database (replace with your actual DB connection logic)
$existingUsernames = ['bob123', 'user2', 'user3']; // Example of existing usernames

// Get the posted username from the request
$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
echo $username;
// Check if the username exists in the database (replace with your actual DB check)
if (in_array($username, $existingUsernames)) {
    echo json_encode(['exists' => true]);
} else {
    echo json_encode(['exists' => false]);
}
?>
