<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['delete-reply']) && isset($_POST['post_id_to_delete'])) {
    $post_id = $_POST['post_id_to_delete'];

    // Check if this is the only post in the topic
    $sql_count_posts = "SELECT COUNT(*) AS post_count FROM posts WHERE post_topic = ?";
    $stmt_count_posts = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt_count_posts, $sql_count_posts)) {
        die('SQL error');
    } else {
        mysqli_stmt_bind_param($stmt_count_posts, "i", $_GET['topic']);
        mysqli_stmt_execute($stmt_count_posts);
        $result_count_posts = mysqli_stmt_get_result($stmt_count_posts);
        $row_count_posts = mysqli_fetch_assoc($result_count_posts);
        
        // If there's only one post, delete the topic along with the post
        if ($row_count_posts['post_count'] == 1) {
            $sql_delete_topic = "DELETE FROM topics WHERE topic_id = ?";
            $stmt_delete_topic = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt_delete_topic, $sql_delete_topic)) {
                die('SQL error');
            } else {
                mysqli_stmt_bind_param($stmt_delete_topic, "i", $_GET['topic']);
                mysqli_stmt_execute($stmt_delete_topic);
            }
            // Redirect to home.php
            header("Location: home.php");
            exit();
        }
    }

    // Prepare and execute the SQL statement to delete the post
    $sql_delete_post = "DELETE FROM posts WHERE post_id = ?";
    $stmt_delete_post = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt_delete_post, $sql_delete_post)) {
        die('SQL error');
    } else {
        mysqli_stmt_bind_param($stmt_delete_post, "i", $post_id);
        mysqli_stmt_execute($stmt_delete_post);

        // Optionally, you can redirect the user to a specific page after deletion
        header("Location: posts.php?topic=".$_GET['topic']);
        exit();
    }
} else {
    // If the delete-reply button was not clicked or post_id_to_delete is not set, redirect the user
    header("Location: posts.php?topic=".$_GET['topic']);
    exit();
}
?>
