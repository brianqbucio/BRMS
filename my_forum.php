<?php
session_start();
include_once 'includes/dbh.inc.php'; // Include your database connection file
include 'includes/HTML-head.php'; // Include your HTML head file
?>

<body>

<?php include 'includes/navbar2.php'; ?> <!-- Include your navbar file -->
<br>
<br>
<br>

<div class="container">
    <h1 class="my-4">My Forum</h1>
    
    <!-- Display topics from database -->
    <div class="row">
        <?php
       $sql = "SELECT t.topic_id, t.topic_subject, t.topic_date, t.topic_by, t.topic_cat, c.cat_name,
       p.post_id, p.post_content, p.post_date, p.post_votes
       FROM topics t
       INNER JOIN categories c ON t.topic_cat = c.cat_id
       INNER JOIN posts p ON t.topic_id = p.post_topic";


        // Adjust SQL query based on the user ID
        if ($_SESSION['userId'] != 14) {
            // If the current user is not the creator, filter topics by the current user's ID
            $sql .= " WHERE t.topic_by = {$_SESSION['userId']}";
        }

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='col-lg-12 mb-4'>";
                echo "<div class='card'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'><a href='posts.php?topic={$row['topic_id']}' style='color: #01497C;'>{$row['topic_subject']}</a></h5>"; 

                echo "<p class='card-text'>{$row['post_content']}</p>"; // Display post content
                echo "</div>";
                echo "<div class='card-footer'>";
                echo "<small class='text-muted'>Category: {$row['cat_name']} | Created: {$row['post_date']}  | Votes: {$row['post_votes']}</small>";
                
                echo "<div class='btn-group mt-2' role='group'>";
                echo "<a href='update_post.php?post_id={$row['post_id']}&action=update' class='btn btn-sm btn-primary mr-2'>Update</a>";
                echo "<button type='button' class='btn btn-sm btn-danger delete-btn' data-post-id='{$row['post_id']}'>Delete</button>";
                echo "</div>";

                
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No topics found.</p>";
        }
        ?>
    </div>
</div>

<br>
<br>
<br>
<br>
<br>
<br>
<br> 
<br>

<?php include 'includes/footer.php'; ?> <!-- Include your footer file -->

<!-- Include necessary scripts -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<!-- Delete confirmation script -->
<script>
    $(document).ready(function() {
        $(".delete-btn").on("click", function() {
            var postId = $(this).data("post-id");
            var confirmDelete = confirm("Are you sure you want to delete this post?");
            if (confirmDelete) {
                window.location.href = "delete_post.php?post_id=" + postId;
            }
        });
    });
</script>

<!-- CSS Styles -->
<style>
    body {
        background-color: #f8f9fa; /* Set background color */
        color: #000; /* Set text color */
    }

    .card {
        transition: all 0.3s;
        border-radius: 10px;
        background-color: #fff; /* Set card background color */
    }

    .card:hover {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    }

    .card-text {
        font-size: 16px;
    }

    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between; /* Align children horizontally */
        align-items: center; /* Align children vertically */
    }

    .card-footer small {
        color: #6c757d;
    }
</style>
</body>
</html>
