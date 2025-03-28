<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['topic'])) {
    $topic = $_GET['topic'];
} else {
    header("Location: index.php");
    exit();
}

include 'includes/HTML-head.php';
?> 

<link href="css/forum-styles.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

<style>
    
    .post-container {
    margin-top: 20px;
    border: 1px solid #ddd;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    padding: 15px;
}

.post-header {
    padding: 10px;
    background-color: #f0f2f5;
    border-bottom: 1px solid #ddd;
    margin-bottom: 10px;
    border-radius: 5px;
}
.post-user {
    display: flex;
    align-items: center;
}

    .post-user {
        display: flex;
        align-items: center;
    }
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
    }
    .user-info {
        display: flex;
        flex-direction: column;
    }
    .user-name {
        font-weight: bold;
        color: #385898;
    }
    .post-text {
        margin-top: 10px;
        word-wrap: break-word;
    }
    .post-actions {
        margin-top: 10px;
        display: flex;
        align-items: center;
    }
    .action-btn {
        margin-right: 10px;
        cursor: pointer;
    }
    .comment {
    margin-top: 10px;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 5px;
    background-color: #fff;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
}

    .comment .user-avatar {
        width: 30px;
        height: 30px;
        margin-right: 5px;
    }
    .comment .user-info {
        display: flex;
        align-items: center;
    }
    .comment .user-name {
        font-size: 14px;
        color: #385898;
        margin-bottom: 0;
    }
    .comment-text {
        margin-top: 5px;
    }
    .comment-section {
        margin-top: 20px;
        display: none; /* Hide the comment section by default */
    }
    .comment-section h2 {
        margin-bottom: 10px;
    }
    .comment-form {
        margin-top: 20px;
    }
    .comment-label {
        font-weight: bold;
        margin-top: 10px;
        color: #385898;
        position: relative; /* Add this */
    }
    .comment-label::before {
        content: "";
        position: absolute;
        top: 50%;
        left: -10px; /* Adjust as needed */
        width: 5px; /* Adjust thickness as needed */
        height: 50%; /* Adjust length as needed */
        background-color: #385898; /* Adjust color as needed */
        transform: translateY(-50%);
    }
    .vote-count {
        margin-left: auto;
        text-align: right;
        font-weight: bold;
        font-size: 14px;
    }
    .comment {
        position: relative; /* Make the comment container relative */
    }

    .trash-icon {
        position: absolute;
        top: 5px; /* Adjust the distance from the top */
        right: 5px; /* Adjust the distance from the right */
        color: #dc3545; /* Change the color of the trash icon */
        cursor: pointer;
    }
    .comment-count {
    background-color: #385898;
    color: white;
    border-radius: 100%; /* Change border-radius to make it circular */
    padding: 2px 7px;
    font-size: 10px;
    position: absolute; /* Position the notification count */
    top: 225px; /* Adjust the top position as needed */
    left: 103px;
}
.thumbs-icon {
    color: #007bff; /* Change to blue color */
}
.heart{
        color: #dc3545; /* Heart color */
    }
    .scroll-btn {
    position: fixed;
    bottom: 20px;
    right: 20px; /* Adjust the distance from the right */
    z-index: 9999;
    background-color: #007bff; /* Background color of the button */
    color: white; /* Text color of the button */
    border: none;
    padding: 10px 15px; /* Adjust padding to make it smaller */
    font-size: 10px; /* Adjust font size */
    cursor: pointer;
    transition: background-color 0.3s;
}

.scroll-btn:hover {
    background-color: #0056b3; /* Change background color on hover */
}

.user-icon {
    margin-left: 5px; /* magdagdag ng espasyo sa kaliwa ng user icon */
    color: #385898; /* kulay ng icon */
    text-decoration: none; /* tanggalin ang default na underline */
}

.message-icon {
    margin-left: 5px; /* magdagdag ng espasyo sa kaliwa ng message icon */
    color: #385898; /* kulay ng icon */
    text-decoration: none; /* tanggalin ang default na underline */
}

</style>
</head>
    
<body>

<?php
include 'includes/navbar2.php';

$sql = "SELECT * FROM topics, categories WHERE topic_id=? AND topic_cat = cat_id";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    die('SQL error');
} else {
    mysqli_stmt_bind_param($stmt, "s", $topic);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (!($forum = mysqli_fetch_assoc($result))) {
        die('SQL error');
    }
}
?>

<br><br><br><br>    
    
<div class="container">
    <div class="col-sm-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color: #014F86;">
                <li class="breadcrumb-item"><a href="#" style="color: #fffffff;">Forums</a></li>
                <li class="breadcrumb-item"><a href="#" style="color: #ffffff;"><?php echo ucwords($forum['cat_name']); ?></a></li>
            </ol>
        </nav>
        <div class="card post-header text-center" style="background-color: #ffffff;">
            <h1><?php echo ucwords($forum['topic_subject']); ?></h1>
            <?php echo ucwords($forum['topic_description']); ?>
        </div>
    </div>
    <div class="col-sm-12">
        <?php
        $sql = "SELECT * FROM posts p JOIN users u ON p.post_by=u.id WHERE p.post_topic=? ORDER BY p.post_id";
        $stmt = mysqli_stmt_init($conn);    

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            die('SQL error');
        } else {
            mysqli_stmt_bind_param($stmt, "s", $topic);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                /// Display post content
echo '<div class="post-container">';
echo '<div class="post-header">
        <span id="post-time-'.$row['post_id'].'"></span>
      </div>';
echo '<div class="post-content">';
echo '<div class="post-user">';
echo '<img src="assets/forum.jpg'.$row['userImg'].'" class="user-avatar" alt="User Avatar">';
echo '<div class="user-info">';
echo '<span class="user-name">'.$row['username'].' <a href="profile.php?id='.$row['id'].'" class="user-icon"><i class="far fa-user"></i></a></span>'; // Idinagdag ang user icon at link papunta sa profile
echo '<small>';
echo $row['business_name'].' <a href="message.php?user_id='.$row['id'].'" class="message-icon"><i class="far fa-envelope"></i></a>'; // Idinagdag ang message icon
echo '</small>';
echo '</div>';
echo '</div>';

echo '<div class="post-text">'.$row['post_content'].'</div>';

// Fetch the count of comments for this post
$sql_comment_count = "SELECT COUNT(*) AS comment_count FROM comments WHERE post_id = ?";
$stmt_comment_count = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt_comment_count, $sql_comment_count)) {
    mysqli_stmt_bind_param($stmt_comment_count, "i", $row['post_id']);
    mysqli_stmt_execute($stmt_comment_count);
    mysqli_stmt_bind_result($stmt_comment_count, $comment_count);
    mysqli_stmt_fetch($stmt_comment_count);
    mysqli_stmt_close($stmt_comment_count); // Close the statement after fetching results
}

// Fetch the count of thumbs-up votes for this post
$sql_thumbs_up = "SELECT COUNT(*) AS thumbs_up FROM postvotes WHERE votePost = ? AND vote = 1";
$stmt_thumbs_up = mysqli_stmt_init($conn);
// ... Rest of your existing code ...


                // Fetch the count of thumbs-up votes for this post
                $sql_thumbs_up = "SELECT COUNT(*) AS thumbs_up FROM postvotes WHERE votePost = ? AND vote = 1";
                $stmt_thumbs_up = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt_thumbs_up, $sql_thumbs_up)) {
                    mysqli_stmt_bind_param($stmt_thumbs_up, "i", $row['post_id']);
                    mysqli_stmt_execute($stmt_thumbs_up);
                    mysqli_stmt_bind_result($stmt_thumbs_up, $thumbs_up);
                    mysqli_stmt_fetch($stmt_thumbs_up);
                    mysqli_stmt_close($stmt_thumbs_up); // Close the statement after fetching results
                }

                // Fetch the count of thumbs-down votes for this post
                $sql_thumbs_down = "SELECT COUNT(*) AS thumbs_down FROM postvotes WHERE votePost = ? AND vote = -1";
                $stmt_thumbs_down = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt_thumbs_down, $sql_thumbs_down)) {
                    mysqli_stmt_bind_param($stmt_thumbs_down, "i", $row['post_id']);
                    mysqli_stmt_execute($stmt_thumbs_down);
                    mysqli_stmt_bind_result($stmt_thumbs_down, $thumbs_down);
                    mysqli_stmt_fetch($stmt_thumbs_down);
                    mysqli_stmt_close($stmt_thumbs_down); // Close the statement after fetching results
                }

                echo '<div class="post-actions">';
                echo '<div class="reaction-icons">';
               // Display thumbs-up icon
                echo '<a id="thumbs-up-'.$row['post_id'].'" class="action-btn thumbs-icon" href="includes/post-vote.inc.php?topic='.$topic.'&post='.$row['post_id'].'&vote=1" onclick="toggleThumbsUp('.$row['post_id'].')"><i class="far fa-thumbs-up fa-lg"></i></a>';
                // Display thumbs-down icon
                echo '<a id="thumbs-down-'.$row['post_id'].'" class="action-btn thumbs-icon" href="includes/post-vote.inc.php?topic='.$topic.'&post='.$row['post_id'].'&vote=-1" onclick="toggleThumbsDown('.$row['post_id'].')"><i class="far fa-thumbs-down fa-lg"></i></a>';
                // Heart icon
              // Sa pagbuo ng post...
                echo '<a class="action-btn" onclick="toggleHeart('.$row['post_id'].')">
                <i id="heart-'.$row['post_id'].'" class="heart ';
                // Check kung nasa listahan ng mga paborito ng user ang post
                if (isset($_SESSION['favorite_status'][$row['post_id']]) && $_SESSION['favorite_status'][$row['post_id']]) {
                echo 'fas'; // Kung nasa listahan ng paborito, gawing puno ang puso
                } else {
                echo 'far'; // Kung hindi nasa listahan ng paborito, gawing outline ang puso
                }
                echo ' fa-heart fa-lg"></i>
                </a>';


                // Comment icon
                echo '<i class="far fa-comment fa-lg action-btn" onclick="toggleComments('.$row['post_id'].')"></i>';
                echo '</div>'; // Close reaction-icons
                // Display count of thumbs-up votes
                echo '<div class="vote-count" style="margin-left: 10px;">Like: '.$thumbs_up.'</div>';
                // Display count of thumbs-down votes
                echo '<div class="vote-count" style="margin-left: 10px;">Dislike: '.$thumbs_down.'</div>';
                echo '</div>'; 
                echo '</div>';// Close post-actions div
                // Add Comments label and comment section
                echo '<div class="comment-count" style="margin-right: 5px;">'.$comment_count.'</div>'; 
                // Display count of comments with red color
                echo '</div>';
                echo '<div class="comment-label" onclick="toggleComments('.$row['post_id'].')" style="cursor: pointer;">Comments</div>';
                echo '<div id="comment-section-'.$row['post_id'].'" class="comment-section">'; // Open comment section container
                // Display comments for this post
                $sql_comments = "SELECT c.*, u.userImg AS comment_userImg, u.username AS comment_username, u.business_name AS comment_business_name FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ?";
                $stmt_comments = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt_comments, $sql_comments)) {
                    mysqli_stmt_bind_param($stmt_comments, "i", $row['post_id']);
                    mysqli_stmt_execute($stmt_comments);
                    $result_comments = mysqli_stmt_get_result($stmt_comments);

                    while ($comment = mysqli_fetch_assoc($result_comments)) {
                        echo '<div class="comment">';
                        echo '<div class="post-user">
                                <img src="uploads/'.$comment['comment_userImg'].'" class="user-avatar" alt="User Avatar">
                                <div class="user-info">
                                    <span class="user-name">';
                        // Check kung ikaw ang nag-comment
                        if ($comment['user_id'] == $_SESSION['userId']) {
                            echo 'You'; // I-display ang "You" kung ikaw ang nag-comment
                        } else {
                            echo $comment['comment_username']; // Kung hindi ikaw ang nag-comment, ipakita ang username ng nag-comment
                        }
                        echo '</span>
                                    <small>'.$comment['comment_business_name'].'</small>
                                </div>
                              </div>';
                        echo '<p class="comment-text">'.$comment['comment_content'].'</p>';
                        // Check kung ikaw ang nag-comment para maipakita ang trash icon
                        if ($comment['user_id'] == $_SESSION['userId']) {
                            echo '<i class="fas fa-trash-alt trash-icon" onclick="deleteComment('.$comment['comment_id'].')"></i>';
                        }
                        echo '</div>';
                    }
                    
                }
                
              // Comment form
echo '<!-- Update your comment form -->
<div class="comment-form">
    <form id="comment-form-'.$row['post_id'].'" enctype="multipart/form-data">
        <input type="hidden" name="post_id" value="'.$row['post_id'].'">
        <textarea class="form-control" name="comment_content" rows="3" placeholder="Write a comment"></textarea>
        <input type="file" name="comment_image"> 
        <div>
        <button type="submit" class="btn btn-primary mt-2" style="background-color: #014F86; color: white;">Post Comment</button>
        </div>
    </form>
</div>';

                echo '</div>'; // Closing post-container div
                echo '</div>'; // Closing post-container div
            }
        }
        ?>
    </div>
</div>
<button id="scrollButton" onclick="scrollToBottom()" class="scroll-btn">
    <i class="fas fa-chevron-down"></i>
</button>
<script>
    var scrollButton = document.getElementById("scrollButton");

    // Function to scroll to the bottom of the page
    function scrollToBottom() {
        // Scroll to the bottom of the page
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });

        // Change the button icon to "up" when scrolled to the bottom
        scrollButton.innerHTML = '<i class="fas fa-chevron-up"></i>';

        // Change the onclick function to scroll to the top when clicked again
        scrollButton.setAttribute("onclick", "scrollToTop()");
    }

    // Function to scroll to the top of the page
    function scrollToTop() {
        // Scroll to the top of the page
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // Change the button icon to "down" when scrolled to the top
        scrollButton.innerHTML = '<i class="fas fa-chevron-down"></i>';

        // Change the onclick function to scroll to the bottom when clicked again
        scrollButton.setAttribute("onclick", "scrollToBottom()");
    }
</script>

<script>
// Function to toggle heart icon and add/remove from favorites
function toggleHeart(post_id) {
    var heartIcon = $('#heart-' + post_id); // Kunin ang heart icon gamit ang post_id
    
    // Check if the heart icon is currently filled or not
    var isFilled = heartIcon.hasClass('fas');

    // Send AJAX request to toggle favorite status
    $.ajax({
        type: "GET",
        url: "add_to_favorites_posts.php",
        data: { action: 'toggle', post_id: post_id },
        success: function(response) {
            if (response === 'added') {
                // Set heart icon to filled if added to favorites
                heartIcon.removeClass('far').addClass('fas');
                alert('Topic successfully added to favorites!');
            } else if (response === 'removed') {
                // Set heart icon to outline if removed from favorites
                heartIcon.removeClass('fas').addClass('far');
                alert('Topic successfully removed from favorites!');
            } else {
                console.log(response); // Log any unexpected response
            }
        }
    });
}



// Function to initialize heart icons based on user's favorites
$(document).ready(function() {
    // Loop through each heart icon and update its color based on user's favorites
    $('[id^="heart-"]').each(function() {
        var post_id = $(this).attr('id').split('-')[1];
        var heartIcon = $(this);

        // Send AJAX request to check if the post is in user's favorites
        $.ajax({
            type: "GET",
            url: "check_favorite_status.php",
            data: { post_id: post_id },
            success: function(response) {
                if (response === 'favorite') {
                    // Set heart icon to filled if the post is in user's favorites
                    heartIcon.removeClass('far').addClass('fas');
                } else {
                    // Set heart icon to outline if the post is not in user's favorites
                    heartIcon.removeClass('fas').addClass('far');
                }
            }
        });
    });
});

</script>

<script>
    function deleteComment(comment_id) {
        // Display confirmation dialog
        var confirmation = confirm("Do you want to delete your comment?");
        
        // If user confirms deletion
        if (confirmation) {
            // Send AJAX request to delete comment
            $.ajax({
                type: "POST",
                url: "delete_comment.php",
                data: { comment_id: comment_id },
                success: function(response) {
                    alert(response); // Display success or error message
                    // Reload the page or remove the deleted comment from the DOM as needed
                    location.reload(); // Reload the page after successful deletion
                }
            });
        }
    }
</script>

<script>
    // Function to toggle comment visibility
    function toggleComments(post_id) {
        $('#comment-section-'+post_id).slideToggle(); // Toggle the comment section of the clicked post
    }
</script>

<script>
    $(document).ready(function() {
        // Handle form submission using AJAX
        $('[id^="comment-form-"]').submit(function(event) {
            event.preventDefault(); // Prevent default form submission

            var form = $(this);
            var formData = new FormData(form[0]);

            $.ajax({
                type: "POST",
                url: "add_comment.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response === "success") {
                        alert("Comment posted successfully!");
                        form[0].reset(); // Reset the form
                        // You can dynamically update the comment section here
                        loadComments(form); // Load comments for the corresponding post
                    } else {
                        alert(response); // Show any error message from the server
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        // Function to load comments for a specific post
        function loadComments(form) {
            var post_id = form.closest('form').attr('id').split('-')[2];
            var commentSection = $('#comment-section-' + post_id);

            $.ajax({
                type: "GET",
                url: "load_comments.php", // Create a PHP file to load comments from the database
                data: { post_id: post_id },
                success: function(response) {
                    commentSection.html(response); // Replace the comment section with the updated comments
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    });
</script>

<script>
    $(document).ready(function() {
        function updateTime() {
            <?php
            mysqli_data_seek($result, 0); // Reset the pointer to the beginning of the result set
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                var postTime = new Date("<?php echo $row['post_date']; ?>");
                var currentTime = new Date();
                var timeDifference = Math.floor((currentTime - postTime) / 1000); // Difference in seconds

                var timeDisplay = '';
                if (timeDifference < 60) {
                    timeDisplay = 'just now';
                } else if (timeDifference < 3600) {
                    var minutes = Math.floor(timeDifference / 60);
                    timeDisplay = minutes + ' minute' + (minutes > 1 ? 's' : '') + ' ago';
                } else if (timeDifference < 86400) {
                    var hours = Math.floor(timeDifference / 3600);
                    timeDisplay = hours + ' hour' + (hours > 1 ? 's' : '') + ' ago';
                } else {
                    var days = Math.floor(timeDifference / 86400);
                    timeDisplay = days + ' day' + (days > 1 ? 's' : '') + ' ago';
                }

                $('#post-time-<?php echo $row['post_id']; ?>').text(timeDisplay);
            <?php } ?>
        }

        updateTime(); // Call the function initially
        setInterval(updateTime, 60000); // Update time every minute
    });
</script>

</body>
</html>