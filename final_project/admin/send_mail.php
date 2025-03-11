<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/vendor/autoload.php';

$smtp_pw = trim(file_get_contents('my.txt'));


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

?>
