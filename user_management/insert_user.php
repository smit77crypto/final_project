<?php
// Assume you're already connected to your database
include('../connect_database.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];

    if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        // Update user data
        $userId = $_POST['user_id'];
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $phone_no = $_POST['phone_no'];
        $gender = $_POST['gender'];
        $username = $_POST['username'];
        $membership_id = $_POST['membership_id'];
    
        // Prepare the update SQL query
        $sql = "UPDATE register SET full_name=?, email=?, phone_no=?, gender=?, username=?, membership_id=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $full_name, $email, $phone_no, $gender, $username, $membership_id, $userId);
    
        // Execute the query and check for success
        if ($stmt->execute()) {
            // Redirect to user management page on success
            header('Location: ../user_management.php?status=updated');
            exit();
        } else {
            // Handle error in case of failure
            echo "Error updating user: " . $stmt->error;
            exit();
        }
    } else {
        // Insert new user data (for adding a new user)
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $phone_no = $_POST['phone_no'];
        $gender = $_POST['gender'];
        $username = $_POST['username'];
        $password = $_POST['password']; // Assuming password is required
        $membership_id = $_POST['membership_id'];
    
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO register (full_name, email, phone_no, gender, username, password, membership_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $full_name, $email, $phone_no, $gender, $username, $hashed_password, $membership_id);
        
            // Execute the query and check for success
            if ($stmt->execute()) {
                // Redirect to user management page on success
                header('Location: ../user_management.php?status=added');
                exit();
            } else {
                // Handle error in case of failure
                echo "Error adding user: " . $stmt->error;
                exit();
            }
    }
}
?>