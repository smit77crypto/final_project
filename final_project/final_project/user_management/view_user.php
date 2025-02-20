<?php
// Database connection
include('../connect_database.php');

// Check if ID is set and is a number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT r.*, m.name AS membership_name 
        FROM register r
        LEFT JOIN membership m ON r.membership_id = m.id
        WHERE r.id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch data
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        header("Location: error.php?message=No employee found with this ID");
        exit;
    }
    $stmt->close();
} else {
    header("Location: error.php?message=Invalid request");
    exit;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employee Details</title>
    <link rel="stylesheet" href="../css/view_user.css">
</head>

<body>
    <div class="card">
        <h2>View Employee Details</h2>

        <div class="form-group">
            <label>Full Name:</label>
            <p><?php echo htmlspecialchars($row['full_name']); ?></p>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <p><?php echo htmlspecialchars($row['email']); ?></p>
        </div>

        <div class="form-group">
            <label>Gender:</label>
            <p><?php echo htmlspecialchars($row['gender'] === 'Other' ? 'N/A' : $row['gender']); ?></p>
        </div>

        <div class="form-group">
            <label>Phone No:</label>
            <p><?php echo htmlspecialchars($row['phone_no']); ?></p>
        </div>

        <div class="form-group">
            <label>Username:</label>
            <p><?php echo htmlspecialchars($row['username']); ?></p>
        </div>

        <div class="form-group">
            <label>Membership:</label>
            <p><?php echo htmlspecialchars($row['membership_name']); ?></p>
        </div>

        <!-- Cancel Button -->
        <div class="button-container">
            <a href="../user_management.php" class="view">Cancel View</a>
        </div>
    </div>
</body>

</html>
