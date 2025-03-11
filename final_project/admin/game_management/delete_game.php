<?php
// Database connection
include('../connect_database.php');
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $game_id = $_GET['id'];
    $stmt = $conn->prepare("UPDATE games SET deleteval = 0 WHERE id = ?");
    $stmt->bind_param("i", $game_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('User deleted successfully!');
                window.location.href = '../game_management.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deleting user.');
                window.location.href = '../game_management.php';
              </script>";
    }

    $stmt->close();
    // Proceed with the update query
} else {
    echo "<script>
            alert('Invalid request.');
            window.location.href = '../game_management.php';
          </script>";
}



$conn->close();
?>
