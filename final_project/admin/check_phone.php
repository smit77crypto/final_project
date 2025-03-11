<?php
include 'db.php'; // Database connection

if (isset($_POST['phone'])) {
    $phone = $_POST['phone'];
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "exists"; // Phone number found
    } else {
        echo "not_exists"; // Phone number not found
    }
    
    $stmt->close();
    $conn->close();
}
?>
