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
            $.get("Api's/term_condition.php", function(data) {
                $("#terms").html(data.replace(/\n/g, '<br>'));
            });
        }
        setInterval(fetchTerms, 1000);
    </script>
</body>
</html>