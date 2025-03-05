<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once '../admin/vendor/autoload.php';

function sendEmail( $name,$email, $phone, $message, $submission_time) {
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
        $mail->addAddress('sohan11903@gmail.com', $name);
        $mail->addReplyTo('sohan11903@gmail.com', 'Sohan');

        // Email content
        $mail->isHTML(true);
        $mail->Subject =  "New Contact Us Message from " . htmlspecialchars($name);
        $mail->Body = '<h3>Hello ' . htmlspecialchars($name) . ',</h3>
            <p>message from user</p>
            <ul>
                <li><b>Name:</b> ' . htmlspecialchars($name) . '</li>
                <li><b>Email:</b> ' . htmlspecialchars($email) . '</li>
                <li><b>Phone No:</b> ' . htmlspecialchars($phone) . '</li>
                <li><b>Message Send At:</b> ' . htmlspecialchars($submission_time) . '</li>
            </ul>
            <h4>Contact Us.</h4>
            <p>'. htmlspecialchars($message).'</p>
            <p>Best Regards,<br> '. htmlspecialchars($name) .' </p>';
        $mail->AltBody = '';

        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Error message to debug email sending issues
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get the raw POST data (JSON format)
    $data = json_decode(file_get_contents('php://input'), true);

    // Get username and password from JSON data
    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $phone = $data['phone'] ?? '';
    $message = $data['message'] ?? '';
    $submission_time = date('Y-m-d H:i:s');
    if (empty($message) || empty($email) || empty($phone) || empty($name)) {
        echo json_encode(['success'=>false,'message' => 'all field are required.']);
        http_response_code(400); // Bad Request
        exit();
    }
    else{
        sendEmail($name, $email, $phone, $message, $submission_time);
        echo json_encode(['success'=>true,'message' => 'mail sent successfully']);
    }
    
}
           

       
