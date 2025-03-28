<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    exit("User not logged in"); // Handle case where user is not logged in
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['action']) && isset($_GET['post_id'])) {
        $action = $_GET['action'];
        $postId = $_GET['post_id'];

        // Ensure postId is properly sanitized to prevent SQL injection
        $postId = mysqli_real_escape_string($conn, $postId);

        // Handle action
        if ($action === "toggle") {
            // Check if the post is already in favorites
            $sql = "SELECT * FROM favorites WHERE user_id=? AND post_id=?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "ii", $_SESSION['userId'], $postId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $num_rows = mysqli_stmt_num_rows($stmt);
                if ($num_rows > 0) {
                    // Remove the post from favorites
                    $sql_delete = "DELETE FROM favorites WHERE user_id=? AND post_id=?";
                    $stmt_delete = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt_delete, $sql_delete)) {
                        mysqli_stmt_bind_param($stmt_delete, "ii", $_SESSION['userId'], $postId);
                        mysqli_stmt_execute($stmt_delete);
                        echo 'removed';
                        exit();
                    }
                } else {
                    // Add the post to favorites
                    $sql_insert = "INSERT INTO favorites (user_id, post_id) VALUES (?, ?)";
                    $stmt_insert = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt_insert, $sql_insert)) {
                        mysqli_stmt_bind_param($stmt_insert, "ii", $_SESSION['userId'], $postId);
                        mysqli_stmt_execute($stmt_insert);
                        echo 'added';
                        exit();
                    }
                }
            }
        } else {
            echo "Invalid action";
        }
    } else {
        echo "Incomplete parameters";
    }
} else {
    echo "Invalid request method";
}
?>