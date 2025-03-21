<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");  // Explicitly set the response type to JSON
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once '../admin/vendor/autoload.php';

function sendEmail( $name,$email, $phone, $message) {
    $smtp_pw = trim(file_get_contents('my.txt'));

    $mail = new PHPMailer(true);
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Gmail SMTP Server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'getinplay.contact@gmail.com'; // Your Gmail
        $mail->Password   = $smtp_pw; // Use Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL
        $mail->Port       = 465;

        // Sender and recipient
        $mail->setFrom('getinplay.contact@gmail.com', 'GetInPlay');
        $mail->addAddress('getinplay.contact@gmail.com', $name);
        $mail->addReplyTo('getinplay.contact@gmail.com', 'GetInPlay');

        // Email content
        $mail->isHTML(true);
        $mail->Subject =  "New Contact Us Message from " . htmlspecialchars($name);
        $mail->Body = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us Message</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .content {
            font-size: 16px;
            color: #555;
        }
        .details {
            margin: 20px 0;
            padding: 15px;
            background-color: #f1f1f1;
            border-radius: 6px;
        }
        .details li {
            list-style: none;
            margin-bottom: 10px;
        }
        .details li b {
            color: #2c3e50;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
           
        }
        .footer a {
            color: #3498db;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">Hello ' . htmlspecialchars($name) . ',</div>
        <div class="content">
            <p>You have received a new message from a user. Below are the details:</p>
            <div class="details">
                <ul>
                    <li><b>Name:</b> ' . htmlspecialchars($name) . '</li>
                    <li><b>Email:</b> ' . htmlspecialchars($email) . '</li>
                    <li><b>Phone No:</b> ' . htmlspecialchars($phone) . '</li>
                    <li><b>Message:</b>' . htmlspecialchars($message) . '</li>
                </ul>
            </div>
            
        </div>
        <div class="footer">
            <p>Best Regards,<br>' . htmlspecialchars($name) . '</p>
            <p style="text-align:center">If you have any questions, feel free to <a href="mailto:getinplay.contact@gmail.com">contact us</a>.</p>
        </div>
    </div>
</body>
</html>
';
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
    if (empty($message) || empty($email) || empty($phone) || empty($name)) {
        echo json_encode(['success'=>false,'message' => 'all field are required.']);
        http_response_code(400); // Bad Request
        exit();
    }
    else{
        sendEmail($name, $email, $phone, $message);
        echo json_encode(['success'=>true,'message' => 'Mail Sent Successfully']);
    }
    
}
           

       