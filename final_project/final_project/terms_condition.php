<?php
include 'connect_database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $terms = $conn->real_escape_string($_POST['terms']);
    
    $sql = "INSERT INTO terms (id, content) VALUES (1, '$terms') ON DUPLICATE KEY UPDATE content='$terms'";
    
    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
    exit;
}

$result = $conn->query("SELECT content, updated_at FROM terms WHERE id=1");
$termsData = $result->fetch_assoc();
$terms = $termsData['content'] ?? '';
$updatedAt = $termsData['updated_at'] ?? '';  
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Terms & Conditions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- <link rel="stylesheet" href="path/to/bootstrap.css" /> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/terms_condition.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <!-- Navbar -->
    <nav>
        <div class="right">
            <a href="admin_login.php" style="text-decoration:none; color:white">
                <div class="btn">
                    <div><i class="fa-solid fa-right-from-bracket"></i></div>
                    <div style="font-weight: bold">LOG OUT</div>
                </div>
            </a>
        </div>
        <div class="left">
            <ul class="ul-box">
                <li class="nav-item">
                    <a class="nav-link" href="admin_home.php" id="termsLink">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" id="gameManagementLink">Game Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="user_management.php" id="userManagementLink">User Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" id="viewSlotsLink">View Slots</a>
                </li>
            </ul>
        </div>
        <div class="right">
            <a href="admin_login.php" style="text-decoration:none; color:white">
                <div class="btn">
                    <div><i class="fa-solid fa-right-from-bracket"></i></div>
                    <div style="font-weight: bold">LOG OUT</div>
                </div>
            </a>
        </div>
    </nav>
    <div class="container">
        <h1>Admin Terms & Conditions</h1>
        <div class="textarea-container">
            <textarea id="terms"><?php echo htmlspecialchars($terms); ?></textarea>
        </div>
        <div class="form-actions">
            <button id="save" class="btn btn-save"><i class="fa-solid fa-cloud-arrow-up"></i></button>
            <button id="cancel" class="btn btn-cancel" onclick="window.location.href='../admin_home.php'"><i
                    class="fa-solid fa-rectangle-xmark"></i></button>
        </div>
    </div>

    <h3>Last Updated At:</h3>
    <div class="update">
        <div><i class="fa-solid fa-calendar-days"></i> : <span id="updatedDate"><?php echo date(' Y-m-d', strtotime($updatedAt)); ?></span></div>
        <div><i class="fa-solid fa-clock"></i> : <span id="updatedTime"><?php echo date(' H:i:s', strtotime($updatedAt)); ?></span></div>
    </div>

    <script>
    $("#save").click(function() {
        $.post("terms_condition.php", {
            terms: $("#terms").val()
        }, function(response) {
            if (response === "success") {
                alert("Terms updated!");

                // Fetch current date and time after update
                const now = new Date();
                const date = now.toISOString().split('T')[0]; // Get the date part (yyyy-mm-dd)
                const time = now.toTimeString().split(' ')[0]; // Get the time part (HH:MM:SS)

                // Update the date and time in the UI
                $("#updatedDate").text(date);
                $("#updatedTime").text(time);
            } else {
                alert("Error updating terms.");
            }
        });
    });


    $("#cancel").click(function() {
        window.location.href = "#"; // Redirect to homepage
    });
    </script>
</body>

</html>