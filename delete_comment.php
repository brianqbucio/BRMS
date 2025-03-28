<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    exit("User not logged in");
}

if (isset($_POST['comment_id'])) {
    $comment_id = $_POST['comment_id'];
    
    // Prepare and execute SQL query to delete comment
    $sql = "DELETE FROM comments WHERE comment_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $comment_id);
        mysqli_stmt_execute($stmt);
        echo "Comment deleted successfully";
    } else {
        echo "Error deleting comment";
    }
} else {
    echo "No comment ID provided";
}
?>