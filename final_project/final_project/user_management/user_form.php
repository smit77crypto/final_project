<?php
$userId = isset($_GET['id']) ? $_GET['id'] : '';
$full_name = $email = $phone_no = $gender = $username = $membership_id = '';
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data and validate
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone_no = trim($_POST['phone_no']);
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $username = trim($_POST['username']);
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $membership_id = $_POST['membership_id'];

    // Validate full name (only alphabets and space)
    if (!preg_match("/^[a-zA-Z\s]+$/", $full_name)) {
        $errors['full_name'] = 'Please enter a valid name (only alphabets and one space allowed).';
    }

    // Validate phone number (10 digits)
    if (!preg_match("/^\d{10}$/", $phone_no)) {
        $errors['phone_no'] = 'Please enter a valid phone number (10 digits).';
    }

    // Validate email (check format)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    // Validate gender
    if (empty($gender)) {
        $errors['gender'] = 'Please select a gender.';
    }

    // Check if email already exists (except for current user)
    include('../connect_database.php');

    // Validate username (check if it exists)
    $sql_check_username = "SELECT * FROM register WHERE username = ? AND id != ?";
    $stmt_check_username = $conn->prepare($sql_check_username);
    $stmt_check_username->bind_param("si", $username, $userId);
    $stmt_check_username->execute();
    $result_check_username = $stmt_check_username->get_result();
    if ($result_check_username->num_rows > 0) {
        $errors['username'] = 'Username already exists. Please choose another one.';
    }

    // Validate password (required for new users)
    if (empty($password) && $userId == '') {
        $errors['password'] = 'Please enter a password.';
    }

    // Validate membership selection
    if (empty($membership_id)) {
        $errors['membership_id'] = 'Please select a membership type.';
    }

    // If no errors, proceed with saving the data to the database
    if (empty($errors)) {
        if ($userId) {
            // Update existing user
            $sql_update = "UPDATE register SET full_name = ?, email = ?, phone_no = ?, gender = ?, username = ?, password = ?, membership_id = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            if (!empty($password)) {
                $password = password_hash($password, PASSWORD_DEFAULT);  // Hash password
            }
            $stmt_update->bind_param("sssssssi", $full_name, $email, $phone_no, $gender, $username, $password, $membership_id, $userId);
            $stmt_update->execute();
        } else {
            // Insert new user
            $sql_insert = "INSERT INTO register (full_name, email, phone_no, gender, username, password, membership_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $password = password_hash($password, PASSWORD_DEFAULT);  // Hash password
            $stmt_insert->bind_param("ssssssi", $full_name, $email, $phone_no, $gender, $username, $password, $membership_id);
            $stmt_insert->execute();
        }

        // Redirect or show success message
        header('Location: ../user_management.php');
        exit();
    }
}

// If updating, fetch the user data
if ($userId) {
    include('../connect_database.php');
    $sql = "SELECT * FROM register WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Pre-fill the form with existing data
        $full_name = $user['full_name'];
        $email = $user['email'];
        $phone_no = $user['phone_no'];
        $gender = $user['gender'];
        $username = $user['username'];
        $membership_id = $user['membership_id'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/user_form.css">
</head>

<body>
    <form class="form-container" id="userForm" method="POST">
        <h2 id="formTitle"><?php echo $userId ? 'Update User' : 'Add User'; ?></h2>

        <!-- Hidden field to carry user ID for update -->
        <?php if ($userId): ?>
            <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
        <?php endif; ?>
        <div class="cen">
        <div class="left">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="full_name" id="name" value="<?php echo htmlspecialchars($full_name); ?>" required>
            <div id="full_name_error" class="error-message"><?php echo $errors['full_name'] ?? ''; ?></div>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone_no" id="phone" value="<?php echo htmlspecialchars($phone_no); ?>" required>
            <div id="phone_error" class="error-message"><?php echo $errors['phone_no'] ?? ''; ?></div>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
            <div id="email_error" class="error-message"><?php echo $errors['email'] ?? ''; ?></div>
        </div>

        <label for="gender">Gender:</label>
        <div class="gender-options">
            <div>
                <input type="radio" id="male" name="gender" value="Male" <?php echo ($gender == 'Male') ? 'checked' : ''; ?>>
                <label for="male">Male</label>
            </div>
            <div>
                <input type="radio" id="female" name="gender" value="Female" <?php echo ($gender == 'Female') ? 'checked' : ''; ?>>
                <label for="female">Female</label>
            </div>
        </div>
        <div id="gender_error" class="error-message"><?php echo $errors['gender'] ?? ''; ?></div>
        </div>
        <div class="right">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>"
                required>
            <div id="username_exists_error" class="error-message"><?php echo $errors['username'] ?? ''; ?></div>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" id="password" <?php echo !$userId ? 'required' : ''; ?>>
            <div id="password_error" class="error-message"><?php echo $errors['password'] ?? ''; ?></div>
        </div>

        <div class="form-group">
            <label>Membership</label>
            <select name="membership_id" id="membership" required>
                <option value="1" <?php echo ($membership_id == '1') ? 'selected' : ''; ?>>Basic</option>
                <option value="2" <?php echo ($membership_id == '2') ? 'selected' : ''; ?>>Silver</option>
                <option value="3" <?php echo ($membership_id == '3') ? 'selected' : ''; ?>>Gold</option>
            </select>
            <div id="membership_error" class="error-message"><?php echo $errors['membership_id'] ?? ''; ?></div>
        </div>
        </div>
        </div>
        
        

        <div class="form-actions">
            <button type="submit" class="btn btn-add"><?php echo $userId ? 'Update' : 'Add'; ?></button>
            <button type="button" class="btn btn-cancel"
                onclick="window.location.href='../user_management.php'">Cancel</button>
        </div>
    </form>
</body>

</html>