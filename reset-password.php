<?php
session_start();
include 'includes/HTML-head.php';

// Include your database connection file here
include 'includes/dbh.inc.php'; // Update this with the correct path

if (isset($_POST['reset-password-submit'])) {
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    // Validate new password
    if ($new_password != $confirm_password) {
        // Passwords don't match, handle error (e.g., display error message)
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        // Check if the username matches the one associated with the email
        $sql_check_username = "SELECT username FROM users WHERE email = ?";
        $stmt_check_username = mysqli_prepare($conn, $sql_check_username);
        mysqli_stmt_bind_param($stmt_check_username, "s", $email);
        mysqli_stmt_execute($stmt_check_username);
        mysqli_stmt_store_result($stmt_check_username);
        mysqli_stmt_bind_result($stmt_check_username, $db_username);
        mysqli_stmt_fetch($stmt_check_username);
        mysqli_stmt_close($stmt_check_username);

        if ($db_username !== $username) {
            // Username does not match, show an alert
            echo "<script>alert('The provided username does not match the email address.');</script>";
        } else {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the user's password in the database using the email
            $sql = "UPDATE users SET password_hash = ? WHERE email = ?";
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $email);
                if (mysqli_stmt_execute($stmt)) {
                    // Password updated successfully
                    echo "<script>alert('Password updated successfully!'); window.location.href = 'login.php';</script>";
                    exit(); // Stop further execution
                } else {
                    // Error updating password
                    echo "<script>alert('Error updating password. Please try again later.');</script>";
                }
                mysqli_stmt_close($stmt);
            } else {
                // Error in preparing the statement
                echo "<script>alert('Error preparing statement. Please try again later.');</script>";
            }
        }
    }
}
?>

<body>
    <?php include 'includes/navbar.php'; ?>

    <section class="form-container">
        <form method="POST" action="reset-password.php">
            <h3>Reset Password</h3>
            <div class="input-control">
                <input type="email" id="email" name="email" placeholder="Enter your email address" required>
            </div>
            <div class="input-control">
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="input-control">
                <input type="password" id="password" name="password" placeholder="Enter new password" required>
            </div>
            <div class="input-control">
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm new password" required>
            </div>
            <input type="submit" value="Reset Password" class="button" name="reset-password-submit">
        </form>
    </section>

    <!-- Bootstrap Js and other scripts -->
    <script>
        document.getElementById("email").focus(); // Focus on the email input field
    </script>
</body>
</html>
