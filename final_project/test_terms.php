<?php
// See the password_hash() example to see where this came from.
$hash = '$2y$10$QjdDFjkAWN3KxiHVpPYjQewMDVDcXlOI8tnyaPGRWB9IYXD3BWceG';

if (password_verify('root', $hash)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}
?>

<!-- <!DOCTYPE html>
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
</html> -->