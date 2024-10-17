<?php
$servername = "localhost"; // Use your actual server name (e.g., "localhost" or an IP address)
$username = "root"; // Your database username
$password = "root"; // Your database password
$dbname = "getinplay"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>