<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

header('Content-Type: application/json');

require_once '../admin/vendor/autoload.php'; // Ensure this points to the correct autoload file for JWT
include 'db_connect.php'; // Ensure db_connect.php is correctly configured

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // Get the current date (or any other format that matches your DB's date format)
    $currentDate = date('Y-m-d'); // Adjust format as needed

    // Prepare the SQL query to select records with a past date
    $query = "SELECT * FROM book_game WHERE book_date < ? ORDER BY id DESC";
    
    if ($stmt = $conn->prepare($query)) {
        // Bind parameters to the query
        $stmt->bind_param("s", $currentDate); // "s" is for string (date format)

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();
        
        // Initialize an empty array to hold the results
        $pastBookings = [];

        // Fetch all rows as an associative array
        while ($row = $result->fetch_assoc()) {
            $pastBookings[] = $row;
        }

        // Return the result as JSON
        echo json_encode([
            'status' => 'success',
            'data' => $pastBookings
        ]);

        // Close the statement
        $stmt->close();
    } else {
        // Handle SQL errors
        echo json_encode([
            'status' => 'error',
            'message' => 'Database query failed.'
        ]);
    }

    // Close the connection
    $conn->close();
}
?>
