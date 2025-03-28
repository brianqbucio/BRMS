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
    
    // Fetch post data from the database based on post ID
    $sql = "SELECT p.*, t.topic_subject, t.topic_description 
            FROM posts p 
            INNER JOIN topics t ON p.post_topic = t.topic_id
            WHERE p.post_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if(mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $postId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        // Check if post exists
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $postContent = $row['post_content']; // Get post content
            $topicSubject = $row['topic_subject']; // Get topic subject
            $topicDescription = $row['topic_description']; // Get topic description
        } else {
            // Redirect to my_forum.php if post does not exist
            header("Location: my_forum.php");
            exit();
        }
    } else {
        // Error handling if SQL statement cannot be prepared
        echo "SQL Error: Unable to fetch post data.";
        exit();
    }
} else {
    // If post ID is not set, redirect to my_forum.php
    header("Location: my_forum.php");
    exit();
}
// Check if form is submitted
if(isset($_POST['submit'])) {
    // Get updated post content and topic subject
    $newPostContent = $_POST['post_content'];
    $newTopicSubject = $_POST['topic_subject'];
    $newTopicDescription = $_POST['topic_description']; // Get updated topic description

    // Update post content, topic subject, and topic description in the database
    $sql = "UPDATE posts p
            INNER JOIN topics t ON p.post_topic = t.topic_id
            SET p.post_content = ?, t.topic_subject = ?, t.topic_description = ?
            WHERE p.post_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if(mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssi", $newPostContent, $newTopicSubject, $newTopicDescription, $postId);
        if(mysqli_stmt_execute($stmt)) {
            // Redirect to my_forum.php after updating post
            header("Location: my_forum.php");
            exit();
        } else {
            // Error handling if execution fails
            echo "Execution failed: " . mysqli_error($conn);
            exit();
        }
    } else {
        // Error handling if SQL statement cannot be prepared
        echo "SQL Error: Unable to prepare statement.";
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Post</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Set background color */
            padding-top: 60px; /* Add top padding to adjust for fixed navbar */
        }

        .container {
            max-width: 800px;
            margin-bottom: 100px; /* Add bottom margin */
        }

        .form-group {
            margin-bottom: 20px;
        }

        textarea.form-control {
            height: 200px;
            resize: vertical; /* Allow vertical resizing of textarea */
        }

        button[type="submit"] {
            width: 100%;
        }
    </style>
</head>
<body>

<?php include 'includes/navbar2.php'; ?> <!-- Include your navbar2 file -->

<div class="container">
    <h1 class="my-4">Update Post</h1>

    <form action="" method="POST">
    <div class="form-group">
        <label for="topic_subject">Topic Subject:</label>
        <input type="text" class="form-control" name="topic_subject" id="topic_subject" value="<?php echo $topicSubject; ?>">
    </div>
    <div class="form-group">
        <label for="topic_description">Topic Description:</label>
        <textarea class="form-control" name="topic_description" id="topic_description" rows="3"><?php echo $topicDescription; ?></textarea>
    </div>
    <div class="form-group">
        <label for="post_content">Post Content:</label>
        <textarea class="form-control" name="post_content" id="post_content" rows="8"><?php echo $postContent; ?></textarea>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Update Post</button>
</form>

</div>

<?php include 'includes/footer.php'; ?> <!-- Include your footer file -->

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>

