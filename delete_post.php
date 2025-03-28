<?php
session_start();
include_once 'includes/dbh.inc.php'; // Include your database connection file

if (!isset($_SESSION['userId'])) {
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

// Check if post ID is set in the URL
if(isset($_GET['post_id'])) {
    $postId = $_GET['post_id'];
    
    // Get the topic ID associated with the post
    $sql_get_topic_id = "SELECT post_topic FROM posts WHERE post_id = ?";
    $stmt_get_topic_id = mysqli_stmt_init($conn);
    
    if(mysqli_stmt_prepare($stmt_get_topic_id, $sql_get_topic_id)) {
        mysqli_stmt_bind_param($stmt_get_topic_id, "i", $postId);
        mysqli_stmt_execute($stmt_get_topic_id);
        mysqli_stmt_bind_result($stmt_get_topic_id, $topicId);
        mysqli_stmt_fetch($stmt_get_topic_id);
        mysqli_stmt_close($stmt_get_topic_id);
        
        // Delete comments associated with the post
        $sql_delete_comments = "DELETE FROM comments WHERE post_id = ?";
        $stmt_delete_comments = mysqli_stmt_init($conn);
        
        if(mysqli_stmt_prepare($stmt_delete_comments, $sql_delete_comments)) {
            mysqli_stmt_bind_param($stmt_delete_comments, "i", $postId);
            mysqli_stmt_execute($stmt_delete_comments);
            mysqli_stmt_close($stmt_delete_comments);
        }
        
        // Delete favorites associated with the post
        $sql_delete_favorites = "DELETE FROM favorites WHERE post_id = ?";
        $stmt_delete_favorites = mysqli_stmt_init($conn);
        
        if(mysqli_stmt_prepare($stmt_delete_favorites, $sql_delete_favorites)) {
            mysqli_stmt_bind_param($stmt_delete_favorites, "i", $postId);
            mysqli_stmt_execute($stmt_delete_favorites);
            mysqli_stmt_close($stmt_delete_favorites);
        }
        
        // Delete post from the database
        $sql_delete_post = "DELETE FROM posts WHERE post_id = ?";
        $stmt_delete_post = mysqli_stmt_init($conn);
        
        if(mysqli_stmt_prepare($stmt_delete_post, $sql_delete_post)) {
            mysqli_stmt_bind_param($stmt_delete_post, "i", $postId);
            mysqli_stmt_execute($stmt_delete_post);
            mysqli_stmt_close($stmt_delete_post);
            
            // Check if the topic has any other posts
            $sql_check_topic_posts = "SELECT COUNT(*) FROM posts WHERE post_topic = ?";
            $stmt_check_topic_posts = mysqli_stmt_init($conn);
            
            if(mysqli_stmt_prepare($stmt_check_topic_posts, $sql_check_topic_posts)) {
                mysqli_stmt_bind_param($stmt_check_topic_posts, "i", $topicId);
                mysqli_stmt_execute($stmt_check_topic_posts);
                mysqli_stmt_bind_result($stmt_check_topic_posts, $postCount);
                mysqli_stmt_fetch($stmt_check_topic_posts);
                mysqli_stmt_close($stmt_check_topic_posts);
                
                // If there are no more posts in the topic, delete the topic
                if($postCount == 0) {
                    $sql_delete_topic = "DELETE FROM topics WHERE topic_id = ?";
                    $stmt_delete_topic = mysqli_stmt_init($conn);
                    
                    if(mysqli_stmt_prepare($stmt_delete_topic, $sql_delete_topic)) {
                        mysqli_stmt_bind_param($stmt_delete_topic, "i", $topicId);
                        mysqli_stmt_execute($stmt_delete_topic);
                        mysqli_stmt_close($stmt_delete_topic);
                    }
                }
            }
            
            // Redirect back to my_forum.php after deletion
            header("Location: my_forum.php");
            exit();
        } else {
            // Error handling if SQL statement cannot be prepared for post deletion
            echo "SQL Error: Unable to delete post.";
            exit();
        }
    }
} else {
    // If post ID is not set, redirect to my_forum.php
    header("Location: my_forum.php");
    exit();
}
?>
