<?php
session_start(); // Start session at the beginning

include('conn/conn.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'endpoint/PHPMailer/src/Exception.php';
require 'endpoint/PHPMailer/src/PHPMailer.php';
require 'endpoint/PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);


if (isset($_POST['forgot-password-submit'])) {
    // Validate email address
    $email = $_POST['email'];

    // Fetch username associated with the email from the database
    $stmt = $conn->prepare("SELECT username FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $username = $result['username'];

        // Generate unique token and store it in the database
        $token = bin2hex(random_bytes(16)); // Generate random token
        $hashed_token = password_hash($token, PASSWORD_DEFAULT); // Hash the token before storing

        // Store $hashed_token in database table associated with $email or $username
        // Example SQL query using PDO
        $stmt = $conn->prepare("UPDATE users SET reset_token = :token WHERE username = :username");
        $stmt->bindParam(':token', $hashed_token);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Send reset email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Send verification email
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'cstd09@gmail.com';
            $mail->Password   = 'tqbf owhs feou gxcx';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('cstd09@gmail.com', 'Business On the GO!');
            $mail->addAddress($email); // User's email

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset';
            $mail->Body    = "To reset your password, click the following link: <a href='http://localhost/BRMS/reset-password.php?token=$token'>Reset Password</a>";
            $mail->send();
           
      // Display alert that email verification has been sent
      echo "<script>alert('Email verification has been sent. Please check your email inbox.');</script>";

      // Redirect user to a page confirming that the reset email has been sent
  } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
} else {
  // Display an alert message if email address is not found
  echo "<script>alert('Email address not found. Please enter a valid email.');</script>";
}
}
?>

<body>
    <?php include 'includes/navbar.php'; ?>

    <section class="form-container">
        <form method="POST" action="forgot-password.php">
            <h3>Forgot Password</h3>
            <div class="input-control">
                <input type="email" id="email" name="email" placeholder="Enter your email address">
            </div>
            <input type="submit" value="Submit" class="button" name="forgot-password-submit">
        </form>
    </section>

    <!-- Bootstrap Js and other scripts -->
</body>
</html>
