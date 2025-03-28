<?php
session_start();
include_once 'includes/dbh.inc.php'; // Include your database connection file

if (!isset($_SESSION['userId'])) {
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

// Check if poll ID is set in the URL
if(isset($_GET['poll_id'])) {
    $pollId = $_GET['poll_id'];
    
    // Delete favorites associated with the poll
    $sql_delete_favorites = "DELETE FROM favorites WHERE poll_id = ?";
    $stmt_delete_favorites = mysqli_stmt_init($conn);
    
    if(mysqli_stmt_prepare($stmt_delete_favorites, $sql_delete_favorites)) {
        mysqli_stmt_bind_param($stmt_delete_favorites, "i", $pollId);
        mysqli_stmt_execute($stmt_delete_favorites);
        mysqli_stmt_close($stmt_delete_favorites);
    }
    
    // Delete poll from the database
    $sql_delete_poll = "DELETE FROM polls WHERE id = ?";
    $stmt_delete_poll = mysqli_stmt_init($conn);
    
    if(mysqli_stmt_prepare($stmt_delete_poll, $sql_delete_poll)) {
        mysqli_stmt_bind_param($stmt_delete_poll, "i", $pollId);
        mysqli_stmt_execute($stmt_delete_poll);
        mysqli_stmt_close($stmt_delete_poll);
        
        // Redirect back to my_poll.php after deletion
        header("Location: my_poll.php");
        exit();
    } else {
        // Error handling if SQL statement cannot be prepared
        echo "SQL Error: Unable to delete poll.";
        exit();
    }
} else {
    // If poll ID is not set, redirect to my_poll.php
    header("Location: my_poll.php");
    exit();
}
?>
