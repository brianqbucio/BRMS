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

</head>

    <?php include 'includes/navbar2.php'; ?>
<br>
<br>
<br>
    <main role="main" class="container">
    <div class="my-3 p-3 bg-white rounded shadow-sm custom-border" style="border: 1px solid #013A63;">
        <h5 class="border-bottom border-gray pb-2 mb-0">All Categories</h5>
        
        
        <?php

            $sql = "select cat_id, cat_name, cat_description, (
                        select count(*) from topics
                        where topics.topic_cat = cat_id
                        ) as forums
                    from categories
                    order by cat_id asc";
            
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
                    
                    echo '<a href="topics.php?cat='.$row['cat_id'].'">
                    <div class="media text-muted pt-3">
                        <img src="assets/forum.jpg" alt="" class="mr-2 rounded div-img ">
                        <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray ">
                            <strong class="d-block" style="color: black;">'.ucwords($row['cat_name']).'</strong><br>
                            <span style="color:black;">'.$row['cat_description'].'</span>
                        </p>
                        <span class="text-right text-primary"> 
                            Forums: '.$row['forums'].' <i class="fa fa-book" aria-hidden="true"></i><br>';
                    
                    echo '</div>';
                }
           }
           
           echo '<small class="d-block text-right mt-3">
                        <a href="create-category.php" class="btn btn-primary">Create Category</a>
                    </small>';
        ?>
        
        
      </div>
    </main>
        
    <?php include 'includes/footer.php'; ?>
        
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
