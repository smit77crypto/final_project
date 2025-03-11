<?php
// Database connection
include('../connect_database.php');

// Check if ID is set and is a number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $game_id = $_GET['id'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM games WHERE id = ?");
    $stmt->bind_param("i", $game_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch data
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        header("Location: error.php?message=No game found with this ID");
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
    <title>View Game Details</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="path/to/bootstrap.css" />
    <!-- You can link Bootstrap or use custom styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/view_game.css">
</head>
<body>
<?php include '../outside_navbar.php' ?>
    <div class="card">
    <div class="aero">
        <button id="exit" class="btn btn-exit" onclick="window.location.href='../game_management.php'"><i class="fa-solid fa-arrow-left"></i></button>
      
      <h2>Game Details</h2>
</div>
        
        <div class="form-group">
            <label>Game Name:</label>
            <p><?php echo htmlspecialchars($row['name']); ?></p>
        </div>

        <div class="form-group">
            <label>Price (30 min):</label>
            <p><?php echo htmlspecialchars($row['half_hour']); ?></p>
        </div>

        <div class="form-group">
            <label>Price (60 min):</label>
            <p><?php echo htmlspecialchars($row['hour']); ?></p>
        </div>

        <div class="form-group">
            <label>Card Image:</label><br>
            <img src="../<?php echo htmlspecialchars($row['card_image'])?>" alt="Game Image" width="150" height="150" style="object-fit: cover;text-align: center;">
        </div>

        <div class="form-group">
            <label>Slider Image:</label><br>
            <img src="../<?php echo ($row['slider_image']); ?>" alt="Slider Image" width="400" height="150">
        </div>

        <div class="form-group">
            <label>Slots:</label>
            <p><?php echo htmlspecialchars($row['slots']); ?></p>
        </div>

    </div>
</body>
</html>