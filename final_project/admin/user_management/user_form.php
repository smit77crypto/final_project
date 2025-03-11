<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

function sendEmail($email, $full_name, $username, $password, $submission_time) {
    $smtp_pw = trim(file_get_contents('../my.txt'));

    $mail = new PHPMailer(true);
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Gmail SMTP Server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sohan11903@gmail.com'; // Your Gmail
        $mail->Password   = $smtp_pw; // Use Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL
        $mail->Port       = 465;

        // Sender and recipient
        $mail->setFrom('sohan11903@gmail.com', 'Sohan');
        $mail->addAddress($email, $full_name);
        $mail->addReplyTo('sohan11903@gmail.com', 'Sohan');

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Registration Successful';
        $mail->Body = '<h3>Hello ' . htmlspecialchars($full_name) . ',</h3>
            <p>Thank you for registering with us. Here are your registration details:</p>
            <ul>
                <li><b>Full Name:</b> ' . htmlspecialchars($full_name) . '</li>
                <li><b>Email:</b> ' . htmlspecialchars($email) . '</li>
                <li><b>Registration Time:</b> ' . htmlspecialchars($submission_time) . '</li>
            </ul>
            <h4>Login Information:</h4>
            <p>You can log in to your account using the following credentials:</p>
            <ul>
                <li><b>Username:</b> ' . htmlspecialchars($username) . '</li>
                <li><b>Password:</b> ' . htmlspecialchars($password) . '</li>
            </ul>
            <p>Click below to log in:</p>
            <p><a href="http://192.168.0.130/assignment-4/index.php" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; display: inline-block;">Login Now</a></p>
            <p>If you want to change your password then first login with your current password and change it.</p>
            <p>Best Regards,<br> GetInPlay</p>';
        $mail->AltBody = 'Hello ' . $full_name . ', Thank you for registering with us.';

        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Error message to debug email sending issues
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}

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
    $user_password = trim($_POST['password']) ? $_POST['password'] : '';
    $membership_id = $_POST['membership_id'];
    // echo $password; die();
    // Validate full name (only alphabets and space)
    if (!preg_match("/^[a-zA-Z\s]+$/", $full_name)) {
        $errors['full_name'] = 'Please enter a valid name (only alphabets and one space allowed).';
    }
    include('../connect_database.php');

    // Validate username (check if it exists)
    $sql_check_phone = "SELECT * FROM register WHERE phone_no = ? AND id != ?";
    $stmt_check_phone = $conn->prepare($sql_check_phone);
    $stmt_check_phone->bind_param("si", $phone_no, $userId);
    $stmt_check_phone->execute();
    $result_check_phone = $stmt_check_phone->get_result();
    if ($result_check_phone->num_rows > 0) {
        $errors['phone_no'] = 'phone No. already exists. Please choose another one.';
    }

    // Validate phone number (10 digits)
    if (!preg_match("/^\d{10}$/", $phone_no)) {
        $errors['phone_no'] = 'Please enter a valid phone number (10 digits).';
    }
    $sql_check_email = "SELECT * FROM register WHERE email = ? AND id != ?";
    $stmt_check_email = $conn->prepare($sql_check_email);
    $stmt_check_email->bind_param("si", $email, $userId);
    $stmt_check_email->execute();
    $result_check_email = $stmt_check_email->get_result();
    if ($result_check_email->num_rows > 0) {
        $errors['email'] = 'Email ID already exists. Please choose another one.';
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
            $sql_update = "UPDATE register SET full_name = ?, email = ?, phone_no = ?, gender = ?, username = ?,membership_id = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ssssssi", $full_name, $email, $phone_no, $gender, $username, $membership_id, $userId);
            $stmt_update->execute();
        } else {
            // Insert new user
            $sql_insert = "INSERT INTO register (full_name, email, phone_no, gender, username, user_password, membership_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            
            $hash_password = password_hash($user_password, PASSWORD_BCRYPT);  // Hash password
            $stmt_insert->bind_param("ssssssi", $full_name, $email, $phone_no, $gender, $username, $hash_password, $membership_id);
            $stmt_insert->execute();

            // Send email with registration details
            $submission_time = date('Y-m-d H:i:s'); // Capture the current time
            sendEmail($email, $full_name, $username, $_POST['password'], $submission_time);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/user_form.css">
</head>

<body>
<?php include '../outside_navbar.php' ?>
    <form class="form-container" id="userForm" method="POST">
        <h2 id="formTitle"><?php echo $userId ? 'Update User' : 'Add User'; ?></h2>

        <!-- Hidden field to carry user ID for update -->
        <?php if ($userId): ?>
        <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
        <?php endif; ?>
        <div class="cen">
            <div class="left1">
                <div class="form-group">
                    <label>Name  <span><?php echo $userId ? '' : '*'; ?></span></label>
                    <input type="text" name="full_name" id="name" value="<?php echo htmlspecialchars($full_name); ?>"
                        required>
                    <div id="full_name_error" class="error-message"><?php echo $errors['full_name'] ?? ''; ?></div>
                </div>

                <div class="form-group">
                    <label>Phone Number <span><?php echo $userId ? '' : '*'; ?></span></label>
                    <input type="text" name="phone_no" id="phone" value="<?php echo htmlspecialchars($phone_no); ?>"
                        required>
                    <div id="phone_error" class="error-message"><?php echo $errors['phone_no'] ?? ''; ?></div>
                </div>

                <div class="form-group">
                    <label>Email  <span><?php echo $userId ? '' : '*'; ?></span></label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>"
                        required>
                    <div id="email_error" class="error-message"><?php echo $errors['email'] ?? ''; ?></div>
                </div>

                <label for="gender">Gender  <span><?php echo $userId ? '' : '*'; ?></span></label>
                <div class="gender-options">
                    <div>
                        <input type="radio" id="male" name="gender" value="Male"
                            <?php echo ($gender == 'Male') ? 'checked' : ''; ?>>
                        <label for="male">Male</label>
                    </div>
                    <div>
                        <input type="radio" id="female" name="gender" value="Female"
                            <?php echo ($gender == 'Female') ? 'checked' : ''; ?>>
                        <label for="female">Female</label>
                    </div>
                </div>
                <div id="gender_error" class="error-message"><?php echo $errors['gender'] ?? ''; ?></div>
            </div>
            <div class="right1">
                <div class="form-group">
                    <label>Username <span><?php echo $userId ? '' : '*'; ?></span></label>
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>"
                        required>
                    <div id="username_exists_error" class="error-message"><?php echo $errors['username'] ?? ''; ?></div>
                </div>

                <div class="form-group">
                    <label>Password <span><?php echo $userId ? '' : '*'; ?></span></label>
                    <input type="password" name="password" id="password" <?php echo !$userId ? 'required' : ''; ?>
                        <?php echo $userId ? 'disabled' : ''; ?> placeholder="<?php echo $userId ? '********' : ''; ?>">
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