<?php 
    include ('./conn/conn.php');
    session_start();

    if (isset($_SESSION['user_verification_id'])) {
        $userVerificationID = $_SESSION['user_verification_id'];
    }

    // Assuming OTP expiration time is set in seconds
    $otp_expiration_time = 120; // Example: 2 minutes
?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System with OTP Verification</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #014F86;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
        }

        .verification-form {
            backdrop-filter: blur(50px);
            color: rgb(255, 255, 255);
            background-color: #fff;
            border: 1px solid #fff;
            padding: 40px;
            color: #000;
            width: 500px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .timer {
            font-size: 1.5rem;
            text-align: center;
            margin-top: 10px;
        }

        .resend-btn {
            display: none; /* Initially hide the resend button */
        }
    </style>
</head>
<body>
    
    <div class="main">

        <!-- OTP Verification Area -->

        <div class="verification-container">

            <div class="verification-form" id="loginForm">
                <h2 class="text-center">OTP Verification</h2>
                <p class="text-center">Please check your email for OTP code.</p>
                <form action="./endpoint/add-user.php" method="POST">
                    <input type="text" name="user_verification_id" value="<?= $userVerificationID ?>" hidden>
                    <input type="number" class="form-control text-center" id="verificationCode" name="verification_code" autocomplete="off"> <!-- Added autocomplete attribute -->
                    <button type="submit" class="btn btn-secondary login-btn form-control mt-4" name="verify">Verify</button>
                </form>
                <button type="button" class="btn btn-link resend-btn form-control mt-2" id="resendBtn">Resend OTP</button>
                <div class="timer" id="timer">Time remaining: <span id="time">2:00</span></div>
            </div>

        </div>

    </div>

    <!-- Bootstrap Js -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <script>
        // Set the initial timer duration (in seconds)
        var countdownDuration = <?= $otp_expiration_time ?>;
        var countdownElement = document.getElementById('time');
        var timer;

        function startCountdown(duration) {
            var startTime = Date.now();
            timer = setInterval(function() {
                var elapsedTime = Math.floor((Date.now() - startTime) / 1000);
                var remainingTime = duration - elapsedTime;

                if (remainingTime <= 0) {
                    clearInterval(timer);
                    countdownElement.innerText = '00:00';
                    alert('OTP has expired. Please request a new one.');
                    document.querySelector('button[name="verify"]').disabled = true;
                    document.getElementById('resendBtn').style.display = 'block'; // Show the resend button
                } else {
                    var minutes = Math.floor(remainingTime / 60);
                    var seconds = remainingTime % 60;
                    countdownElement.innerText = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
                }
            }, 1000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            startCountdown(countdownDuration);
        });

        // Resend Button Click Event
        document.getElementById('resendBtn').addEventListener('click', function() {
            // Hide the resend button again
            this.style.display = 'none';
            // Make an AJAX call or form submission to resend OTP
            // Example AJAX call
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'resend-otp.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status == 200) {
                    alert('OTP resent successfully. Please check your email.');
                    // Reset timer and enable verify button
                    startCountdown(countdownDuration);
                    document.querySelector('button[name="verify"]').disabled = false;
                } else {
                    alert('Error resending OTP. Please try again.');
                }
            };
            xhr.send();
        });
    </script>
</body>
</html>
