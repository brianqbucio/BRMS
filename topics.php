<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

include 'includes/HTML-head.php';
?>

<link rel="stylesheet" type="text/css" href="css/list-page.css">
<style>
    .topic-subject {
    color: #000000;
}
.forum-link {
    color: #000000;
}


    </style>
</head>


<?php
            
include 'includes/navbar2.php';

if(isset($_GET['cat']))
{
    $sql = "select * from categories where cat_id = ?";
                
    $stmt = mysqli_stmt_init($conn);  

    if (!mysqli_stmt_prepare($stmt, $sql))
    {
        die('SQL error');
    }
    else
    {
        mysqli_stmt_bind_param($stmt, "s", $_GET['cat']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $category = mysqli_fetch_assoc($result);
    }
}
?>
<br>
<br>
<br>
<br>
<main role="main" class="container">
    <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded shadow-sm">
        <div class="lh-100">
            <h1 class="mb-0 text-white lh-100">Business Forums</h1>
        </div>
    </div>  
            
    <div class="my-3 p-3 bg-white rounded shadow-sm" style="border: 1px solid #013A63;">
        <h5 class="border-bottom border-gray pb-2 mb-0">
            <?php 
            if(isset($_GET['cat']))
            {
                echo '<a href="forum.php" class="forum-link">Forums</a>

                / <span style="color: #709fea ">'.ucwords($category['cat_name'])."</span>";
            }
            else
            {
                echo 'All Forums';
            }
            ?>
        </h5>
        
        <?php

        $sql = "select topic_id, topic_subject, topic_date, topic_cat, topic_by, userImg, id, username, cat_name, (
            select sum(post_votes)
            from posts
            where post_topic = topic_id
            ) as upvotes
            from topics, users, categories 
            where ";
            
        if(isset($_GET['cat']))
        {
            $sql .= "topic_cat = " . $_GET['cat'] . " and ";
        }
            
        $sql .= "topics.topic_by = users.id
            and topics.topic_cat = categories.cat_id
            order by topic_id asc ";
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
        <img src="assets/forum.jpg" alt="" class="mr-2 rounded div-img">
        <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
            <strong class="d-block"><span class="topic-subject">'.ucwords($row['topic_subject']).'</span></strong></a>
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
            <a href="create-topic.php" class="btn btn-primary">Create A Forum</a>
        </small>
        
    </div>
</main>
        
<?php include 'includes/footer.php'; ?>
</body>
</html>
