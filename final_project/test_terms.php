<?php
include 'final_project/connect_database.php';
$result = $conn->query("SELECT content FROM terms WHERE id=1");
$terms = $result->fetch_assoc()['content'] ?? 'No terms available.';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Terms & Conditions</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>User Terms & Conditions</h1>
    <p id="terms"><?php echo nl2br(htmlspecialchars($terms)); ?></p>
    <script>
        function fetchTerms() {
            $.get("fetch_terms.php", function(data) {
                $("#terms").html(data);
            });
        }
        setInterval(fetchTerms, 5000);
    </script>
</body>
</html>