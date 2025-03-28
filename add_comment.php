<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['userId'];
    $comment_content = $_POST['comment_content'];

    // Check if a file was uploaded
    if ($_FILES['comment_image']['name']) {
        $file_name = $_FILES['comment_image']['name'];
        $file_tmp = $_FILES['comment_image']['tmp_name'];
        $file_type = $_FILES['comment_image']['type'];

        // Move the uploaded file to a folder on the server
        move_uploaded_file($file_tmp, "uploads/" . $file_name);

        // Save the file path in the database along with the comment
        $comment_content .= '<br><img src="uploads/' . $file_name . '" alt="Comment Image" style="width: 350px; height: 250px;">';
    }

    $sql = "INSERT INTO comments (post_id, user_id, comment_content, comment_image) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "iiss", $post_id, $user_id, $comment_content, $image_path);
        mysqli_stmt_execute($stmt);
        echo "success"; // Send success response
    } else {
        echo "SQL error";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    // Send error response if the form was not submitted properly
    echo "Form submission error";
}
?>