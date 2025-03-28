<?php
session_start();
require 'includes/dbh.inc.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    echo 'not_logged_in'; // Return a response indicating user is not logged in
    exit();
}

// Check if post_id is set in the request
if (!isset($_GET['post_id'])) {
    echo 'error'; // Return a response indicating error
    exit();
}

$post_id = $_GET['post_id'];
$user_id = $_SESSION['userId'];

// Query to check if the post is in user's favorites
$sql = "SELECT * FROM favorites WHERE user_id = ? AND post_id = ?";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo 'error'; // Return a response indicating error
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $post_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['favorite_status'][$post_id] = true; // Update session variable if post is in user's favorites
        echo 'favorite'; // Return a response indicating post is in user's favorites
    } else {
        $_SESSION['favorite_status'][$post_id] = false; // Update session variable if post is not in user's favorites
        echo 'not_favorite'; // Return a response indicating post is not in user's favorites
    }
}

mysqli_stmt_close($stmt); // Close the statement
mysqli_close($conn); // Close the connection
?>