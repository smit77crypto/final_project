<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
include 'connect_database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $terms = $_POST['terms'];

    // Prepare the SQL statement
    $sql = "INSERT INTO terms (id, content) VALUES (1, ?) ON DUPLICATE KEY UPDATE content=?";
    
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters
        $stmt->bind_param('ss', $terms, $terms);  // 'ss' means two string parameters

        // Execute the statement
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "error: " . $conn->error;
    }
    
    exit;
}

$result = $conn->query("SELECT content, updated_at FROM terms WHERE id=1");
$termsData = $result->fetch_assoc();
$terms = $termsData['content'] ?? '';
$updatedAt = $termsData['updated_at'] ?? '';

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Terms and Conditions</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="path/to/bootstrap.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tiny.cloud/1/ipqp5i9ny4hxn2ss2cnvwxmegyb7tn7hp6lrw2ijz0bq5gd6/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>

    <!-- Include jQuery before your custom script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/terms_condition.css">
    

    <style>
    #lastUpdated {
        margin-top: 20px;
        font-size: 14px;
        color: gray;
    }
    </style>
</head>

<body>
    <?php include "navbar.php"; ?>
    <div class="container">
        <h1>Terms & Condition</h1>

        <!-- Form to submit the terms and conditions -->
        <div class="textarea-container">
            <textarea id="termsEditor" name="terms"><?php echo htmlspecialchars($terms); ?></textarea>
        </div>
        <div class="form-actions">
                <button type="submit" id="save" class="btn btn-add">Add</button>
                <button type="button" class="btn btn-cancel"
                    onclick="window.location.href='admin_home.php'">Cancel</button>
        </div>
        
    </div>
    <h3>Last Updated At:</h3>
    <div class="con">
        <div class="update">
            <div><i class="fa-solid fa-calendar-days"></i> : <span
                    id="updatedDate"><?php echo date('Y-m-d', strtotime($updatedAt)); ?></span></div>
            <div><i class="fa-solid fa-clock"></i> : <span
                    id="updatedTime"><?php echo date('H:i:s', strtotime($updatedAt)); ?></span></div>
        </div>
    </div>
        



    <script>
    // Initialize TinyMCE editor
    tinymce.init({
        selector: '#termsEditor', // Correct selector for TinyMCE
        height: 400,
        plugins: 'link image code',
        toolbar: 'undo redo | styleselect | bold italic | link image | code',
        menubar: false
    });

    // Handle save button click
    // Handle save button click
    $("#save").click(function() {
        $.post("terms_condition.php", {
            terms: tinymce.get('termsEditor').getContent() // Get content from TinyMCE editor
        }, function(response) {
            if (response === "success") {
                alert("Terms updated!");

                // Update the UI with the new date and time
                const now = new Date();
                const date = now.toISOString().split('T')[0]; // Get the date part
                const time = now.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                }); // 12-hour format with AM/PM

                $("#updatedDate").text(date);
                $("#updatedTime").text(time);
            } else {
                alert("Error updating terms.");
            }
        });
    });
    </script>
</body>

</html>