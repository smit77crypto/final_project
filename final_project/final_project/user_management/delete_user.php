<?php
// Database connection
include('../connect_database.php');

// Check if ID is provided and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = $_GET['id'];

    // Update deleteval to 0 instead of deleting
    $stmt = $conn->prepare("UPDATE register SET deleteval = 0 WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('User deleted successfully!');
                window.location.href = '../user_management.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deleting user.');
                window.location.href = '../user_management.php';
              </script>";
    }

    $stmt->close();
} else {
    echo "<script>
            alert('Invalid request.');
            window.location.href = '../user_management.php';
          </script>";
}

$conn->close();
?>
