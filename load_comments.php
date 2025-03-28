<?php
// load_comments.php

// Include the necessary files and initialize the session if needed
session_start();
require 'includes/dbh.inc.php';

// Check if the post_id is set in the GET request
if(isset($_GET['post_id'])) {
    // Sanitize the post_id
    $post_id = mysqli_real_escape_string($conn, $_GET['post_id']);

    // Query to get comments for the specified post
    $sql = "SELECT c.*, u.userImg AS comment_userImg, u.username AS comment_username, u.business_name AS comment_business_name FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $post_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Check if there are comments for the post
        if(mysqli_num_rows($result) > 0) {
            // Loop through the comments and display them
          // Loop through the comments and display them
while ($comment = mysqli_fetch_assoc($result)) {
    echo '<div class="comment">';
    echo '<div class="post-user">
            <img src="uploads/'.$comment['comment_userImg'].'" class="user-avatar" alt="User Avatar">
            <div class="user-info">';
    // Check if the comment belongs to the current user
    if ($comment['user_id'] == $_SESSION['userId']) {
        echo '<span class="user-name">You</span>'; // I-display ang "You" kung ikaw ang nag-comment
    } else {
        echo '<span class="user-name">'.$comment['comment_username'].'</span>'; // Kung hindi ikaw ang nag-comment, ipakita ang username ng nag-comment
    }
    echo '<small>'.$comment['comment_business_name'].'</small>
          </div>
          </div>';
    echo '<p class="comment-text">'.$comment['comment_content'].'</p>';
    // Check if the comment belongs to the current user
    if ($comment['user_id'] == $_SESSION['userId']) {
        // If yes, display the trash icon
        echo '<i class="fas fa-trash-alt trash-icon" onclick="deleteComment('.$comment['comment_id'].')"></i>';
    }
    echo '</div>';
}

        } else {
            // If there are no comments for the post
            echo '<p>No comments yet.</p>';
        }
    } else {
        // If the SQL statement is not prepared
        echo 'SQL error';
    }

    // Add the comment form HTML
    echo '<!-- Comment form -->
    <div class="comment-form">
        <form id="comment-form-'.$post_id.'" onsubmit="submitComment(event, '.$post_id.')">
            <input type="hidden" name="post_id" value="'.$post_id.'">
            <textarea class="form-control" name="comment_content" rows="3" placeholder="Write a comment"></textarea>
            <input type="file" name="comment_image"> 
            <div>
            <button type="submit" class="btn btn-primary mt-2" style="background-color: #014F86; color: white;">Post Comment</button>
            </div>
        </form>
    </div>';
} else {
    // If the post_id is not set in the GET request
    echo 'Post ID is not set.';
}
?>

<script>
    function submitComment(event, post_id) {
        event.preventDefault(); // Prevent default form submission

        var formData = new FormData(document.getElementById('comment-form-' + post_id));

        // Send AJAX request to submit the form data
        $.ajax({
            type: 'POST',
            url: 'add_comment.php', // Modify the URL to your PHP script that handles comment submission
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response === 'success') {
                    // Comment posted successfully
                    // Reload the comment section to display the new comment
                    loadComments(post_id);
                    alert('Comment posted successfully!'); // Display alert message
                } else {
                    // Handle any errors or display a message
                    console.log(response);
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX errors
                console.error(error);
            }
        });
    }

    // Function to load comments for the specified post
    function loadComments(post_id) {
        // Send AJAX request to load comments for the specified post
        $.ajax({
            type: 'GET',
            url: 'load_comments.php',
            data: { post_id: post_id },
            success: function(response) {
                // Replace the existing comment section with the updated comments
                $('#comment-section-' + post_id).html(response);
            },
            error: function(xhr, status, error) {
                // Handle AJAX errors
                console.error(error);
            }
        });
    }
</script>