<?php
$servername = "localhost"; // Use your actual server name (e.g., "192.168.0.130" or an IP address)
$username = "root"; // Your database username
$password = "bgmi"; // Your database password
$dbname = "getinplay"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>