<?php
include 'db.php'; // Database connection

if (isset($_POST['phone_no'])) {
    $phone = $_POST['phone_no'];
    
    $stmt = $conn->prepare("SELECT id FROM register WHERE phone_no = ?");
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
