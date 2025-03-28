<?php
session_start();
include('./conn/conn.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_SESSION['user_verification_id'])) {
    $userVerificationID = $_SESSION['user_verification_id'];

    // Generate a new verification code
    $newVerificationCode = rand(100000, 999999);

    // Update the database with the new verification code
    $stmt = $conn->prepare("UPDATE users SET verification_code = :verification_code WHERE id = :user_verification_id");
    $stmt->execute([
        'verification_code' => $newVerificationCode,
        'user_verification_id' => $userVerificationID
    ]);

    // Retrieve user's email
    $stmt = $conn->prepare("SELECT email FROM users WHERE id = :user_verification_id");
    $stmt->execute(['user_verification_id' => $userVerificationID]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $userData['email'];

    require './endpoint/PHPMailer/src/Exception.php';
    require './endpoint/PHPMailer/src/PHPMailer.php';
    require './endpoint/PHPMailer/src/SMTP.php';

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP(); 
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true; 
        $mail->Username   = 'cstd09@gmail.com'; // Replace with your Gmail email
        $mail->Password   = 'tqbf owhs feou gxcx'; // Replace with your Gmail password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;                                  

        //Recipients
        $mail->setFrom('cstd09@gmail.com', 'Business On the GO!');
        $mail->addAddress($email);   
        $mail->addReplyTo('cstd09@gmail.com', 'Business On the GO!'); 
    
        //Content
        $mail->isHTML(true);  
        $mail->Subject = 'New Verification Code';
        $mail->Body = '
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 16px;
                    line-height: 1.6;
                    color: #333;
                }
                .verification-code {
                    font-size: 24px;
                    font-weight: bold;
                    color: #007bff;
                }
                .footer {
                    margin-top: 20px;
                    font-size: 14px;
                    color: #666;
                }
            </style>
        </head>
        <body>
            <p>Dear User,</p>
            <p>Your New Verification code is: <span class="verification-code">' . $newVerificationCode . '</span></p>
            <p>Please use this code to verify your account.</p>
            <div class="footer">
                <p>If you did not request this code, please ignore this email.</p>
                <p>Thank you,<br> Business On The GO!</p>
            </div>
        </body>
        </html>';
        
        // Send the email
        $mail->send();

        // Set a new session variable to avoid spamming
        $_SESSION['otp_resend'] = true;

        // Return success response
        http_response_code(200);
        exit('OTP resent successfully');
    } catch (Exception $e) {
        // Return error response
        http_response_code(500);
        exit('Error resending OTP: ' . $mail->ErrorInfo);
    }
} else {
    // Return error response if session is not set
    http_response_code(400);
    exit('Session expired. Please refresh the page.');
}
?>