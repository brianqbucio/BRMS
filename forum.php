<?php
session_start();
require 'includes/dbh.inc.php';

if(!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

include 'includes/HTML-head.php';
?>  

<link rel="stylesheet" type="text/css" href="css/list-page.css">
<style>
    .bordered-div {
        border: 2px solid #013A63;
    }
</style>

</head>

<?php include 'includes/navbar2.php'; ?>
<br>
<br>
<br>
<main role="main" class="container">
    <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded shadow-sm">
        <div class="lh-100">
            <h1 class="mb-0 text-white lh-100">Business Forums</h1>
        </div>
    </div>

    <div class="my-3 p-3 bg-white rounded shadow-sm bordered-div">
        <h5 class="border-bottom border-gray pb-2 mb-0">Top Categories</h5>
        
        
        <?php
$sql = "SELECT cat_id, cat_name, cat_description, (
            SELECT COUNT(*) FROM topics
            WHERE topics.topic_cat = cat_id
        ) AS forums
        FROM categories
        ORDER BY forums DESC, cat_id ASC
        LIMIT 5";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    die('SQL error');
} else {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<a href="topics.php?cat='.$row['cat_id'].'">
            <div class="media text-muted pt-3">
                <img src="assets/forum.jpg" alt="" class="mr-2 rounded div-img">
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <strong class="d-block text-gray-dark" style="color: black;">'.ucwords($row['cat_name']).'</strong></a>
                    <br>'.$row['cat_description'].'
                    </p>
                    <span class="text-right text-primary"> 
                        Forums: '.$row['forums'].' <i class="fa fa-book" aria-hidden="true"></i><br>';
        
                            
                            echo '</div>';
                        }
                   }
                   
                   
                    echo '<small class="d-block text-right mt-3">
                    <a href="create-category.php" class="btn btn-primary" style="background-color: #014F86;">Create Category</a>
                    <a href="categories.php" class="btn btn-primary" style="background-color: #2C7DA0;">All Categories</a>
                </small>';
                ?>
                
              </div>
                   
            
            
            
      <div class="my-3 p-3 bg-white rounded shadow-sm bordered-div">
        <h5 class="border-bottom border-gray pb-2 mb-0">Top Forums</h5>
        
        <?php

$sql = "SELECT DISTINCT topics.topic_id, topics.topic_subject, topics.topic_date, topics.topic_cat, topics.topic_by, users.userImg, users.id, users.username, categories.cat_name, (
    SELECT SUM(post_votes)
    FROM posts
    WHERE posts.post_topic = topics.topic_id
) AS upvotes
FROM topics
INNER JOIN users ON topics.topic_by = users.id
INNER JOIN categories ON topics.topic_cat = categories.cat_id
LEFT JOIN posts ON topics.topic_id = posts.post_topic
GROUP BY topics.topic_id
ORDER BY upvotes DESC, topics.topic_id ASC 
LIMIT 5";

            $stmt = mysqli_stmt_init($conn);    

            if (!mysqli_stmt_prepare($stmt, $sql))
            {
                die('SQL error');
            }
            else
            {
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                while ($row = mysqli_fetch_assoc($result))
                {
                    
                    echo '<a href="posts.php?topic='.$row['topic_id'].'">
                        <div class="media text-muted pt-3">
                            <img src="uploads/'.$row['userImg'].'" alt="" class="mr-2 rounded div-img">
                            <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                              <strong class="d-block text-gray-dark" style="color: black;">'.ucwords($row['topic_subject']).'</strong></a>
                              <span class="text-primary" style="color: #000000;">'.ucwords($row['username']).'</span><br>
                              '.date("F jS, Y", strtotime($row['topic_date'])).'
                            </p>
                            <span class="text-primary text-center">
                                <i class="fa fa-chevron-up" aria-hidden="true"></i><br>
                                    '.$row['upvotes'].'<br>';
                    
                    echo '</span>
                            </div>';
                }
           }
        ?>
        
        <small class="d-block text-right mt-3">
    <a href="create-topic.php" class="btn btn-primary" style="background-color: #014F86;">Create A Forum</a>
    <a href="topics.php" class="btn btn-primary" style="background-color: #2C7DA0;">All Forums</a>
</small>

        
      </div>
    </main>
        
<?php include 'includes/footer.php'; ?>
        
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
