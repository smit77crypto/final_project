<?php
    $servername = "localhost";
    $username1 = "root"; // Change if you have a different username
    $password = "root"; // Change if you have a MySQL password
    $dbname = "getinplay";
    
    // Create connection
    $conn = new mysqli($servername, $username1, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
    }
?>