<?php
session_start();
require 'includes/dbh.inc.php';

if (isset($_GET['post_id']) && isset($_GET['type'])) {
    $post_id = $_GET['post_id'];
    $type = $_GET['type']; // 'up' for thumbs-up, 'down' for thumbs-down

    // Check if the user is logged in
    if (isset($_SESSION['userId'])) {
        $user_id = $_SESSION['userId'];

        // Check if the user has already voted on this post
        $check_vote_sql = "SELECT * FROM postvotes WHERE votePost = ? AND userId = ?";
        $stmt_check_vote = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt_check_vote, $check_vote_sql)) {
            mysqli_stmt_bind_param($stmt_check_vote, "ii", $post_id, $user_id);
            mysqli_stmt_execute($stmt_check_vote);
            $result_check_vote = mysqli_stmt_get_result($stmt_check_vote);

            if (mysqli_num_rows($result_check_vote) > 0) {
                // User has already voted, update the existing vote
                $vote_row = mysqli_fetch_assoc($result_check_vote);
                if ($vote_row['vote'] == 1 && $type == 'up') {
                    // If the user has already voted thumbs-up and clicks thumbs-up again, remove the vote
                    $delete_vote_sql = "DELETE FROM postvotes WHERE votePost = ? AND userId = ?";
                    $stmt_delete_vote = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt_delete_vote, $delete_vote_sql)) {
                        mysqli_stmt_bind_param($stmt_delete_vote, "ii", $post_id, $user_id);
                        mysqli_stmt_execute($stmt_delete_vote);
                        echo 'removed';
                    }
                } elseif ($vote_row['vote'] == -1 && $type == 'down') {
                    // If the user has already voted thumbs-down and clicks thumbs-down again, remove the vote
                    $delete_vote_sql = "DELETE FROM postvotes WHERE votePost = ? AND userId = ?";
                    $stmt_delete_vote = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt_delete_vote, $delete_vote_sql)) {
                        mysqli_stmt_bind_param($stmt_delete_vote, "ii", $post_id, $user_id);
                        mysqli_stmt_execute($stmt_delete_vote);
                        echo 'removed';
                    }
                } else {
                    // Otherwise, update the existing vote
                    $update_vote_sql = "UPDATE postvotes SET vote = ? WHERE votePost = ? AND userId = ?";
                    $stmt_update_vote = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt_update_vote, $update_vote_sql)) {
                        if ($type == 'up') {
                            mysqli_stmt_bind_param($stmt_update_vote, "iii", 1, $post_id, $user_id);
                        } elseif ($type == 'down') {
                            mysqli_stmt_bind_param($stmt_update_vote, "iii", -1, $post_id, $user_id);
                        }
                        mysqli_stmt_execute($stmt_update_vote);
                        echo $type;
                    }
                }
            } else {
                // User hasn't voted on this post, insert a new vote
                $insert_vote_sql = "INSERT INTO postvotes (votePost, userId, vote) VALUES (?, ?, ?)";
                $stmt_insert_vote = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt_insert_vote, $insert_vote_sql)) {
                    if ($type == 'up') {
                        mysqli_stmt_bind_param($stmt_insert_vote, "iii", $post_id, $user_id, 1);
                    } elseif ($type == 'down') {
                        mysqli_stmt_bind_param($stmt_insert_vote, "iii", $post_id, $user_id, -1);
                    }
                    mysqli_stmt_execute($stmt_insert_vote);
                    echo $type;
                }
            }
        }
    } else {
        echo 'not_logged_in';
    }
} else {
    echo 'error';
}
?>
