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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="path/to/bootstrap.css" />
    <!-- You can link Bootstrap or use custom styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/view_user.css">
</head>

<body>
<?php include '../outside_navbar.php' ?>
    <div class="card">
        <div class="aero">
        <button id="exit" class="btn btn-exit" onclick="window.location.href='../user_management.php'"><i class="fa-solid fa-arrow-left"></i></button>
      
      <h2>Employee Details</h2>
</div>
    
        
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


    </div>
</body>

</html>
