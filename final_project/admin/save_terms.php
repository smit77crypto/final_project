<?php
// Database connection
include 'connect_database.php';

// Check if the form was submitted via POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['terms'])) {
    // Sanitize the input to prevent XSS and other attacks
    $terms_content = mysqli_real_escape_string($conn, $_POST['terms']);

    // Check if the terms already exist in the database (assuming one record for terms)
    $sql = "SELECT * FROM terms WHERE id = 1";  // Assuming there's only one record
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Update the existing record
        $update_sql = "UPDATE terms SET content = '$terms_content', updated_at = NOW() WHERE id = 1";
        if ($conn->query($update_sql) === TRUE) {
            echo json_encode(['success' => true, 'message' => 'Terms and Conditions updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating record: ' . $conn->error]);
        }
    } else {
        // Insert a new record if it doesn't exist
        $insert_sql = "INSERT INTO terms (content) VALUES ('$terms_content')";
        if ($conn->query($insert_sql) === TRUE) {
            echo json_encode(['success' => true, 'message' => 'Terms and Conditions saved successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error inserting record: ' . $conn->error]);
        }
    }

    // Close the database connection
    $conn->close();
}
?>
