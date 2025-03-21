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

    // Prepare the SQL queries to select records with a past date
    $query1 = "SELECT bg.*, g.name AS game_name 
               FROM book_game bg 
               LEFT JOIN games g ON bg.game_id = g.id 
               WHERE bg.book_date < ? AND bg.DELETED = 1 
               ORDER BY bg.id DESC";
               
    $query2 = "SELECT bg.*, g.name AS game_name 
               FROM book_game bg 
               LEFT JOIN games g ON bg.game_id = g.id 
               WHERE bg.book_date < ? AND bg.DELETED = 0 
               ORDER BY bg.id DESC"; // Assuming you want to check non-deleted bookings here

    // Initialize arrays for past and canceled bookings
    $pastBookings = [];
    $cancleBookings = [];

    // Prepare first statement
    if ($stmt1 = $conn->prepare($query1)) {
        // Bind parameters to the query
        $stmt1->bind_param("s", $currentDate); // "s" is for string (date format)
        
        // Execute the query
        $stmt1->execute();
        
        // Get the result for the first query
        $result1 = $stmt1->get_result();
        
        // Fetch all rows as an associative array
        while ($row = $result1->fetch_assoc()) {
            // Add the fetched data (including game_name) to the pastBookings array
            $pastBookings[] = $row;
        }

        // Close the first statement
        $stmt1->close();
    } else {
        // Handle errors for the first query
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to prepare query for past bookings.'
        ]);
        exit;
    }

    // Prepare second statement
    if ($stmt2 = $conn->prepare($query2)) {
        // Bind parameters to the query
        $stmt2->bind_param("s", $currentDate); // "s" is for string (date format)
        
        // Execute the query
        $stmt2->execute();
        
        // Get the result for the second query
        $result2 = $stmt2->get_result();
        
        // Fetch all rows as an associative array
        while ($row = $result2->fetch_assoc()) {
            // Add the fetched data (including game_name) to the cancleBookings array
            $cancleBookings[] = $row;
        }

        // Close the second statement
        $stmt2->close();
    } else {
        // Handle errors for the second query
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to prepare query for canceled bookings.'
        ]);
        exit;
    }

    // Return the result as JSON including the count
    echo json_encode([
        'status' => 'success',
        'data' => $pastBookings,
        'pastBookingsCount' => count($pastBookings), // Count of past bookings
        'cancle' => $cancleBookings,
        'cancleBookingsCount' => count($cancleBookings) // Count of canceled bookings
    ]);

    // Close the connection
    $conn->close();
}
?>
