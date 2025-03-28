<?php
session_start();
include_once 'includes/dbh.inc.php';
include 'includes/HTML-head.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}
?>

<body>
<?php include 'includes/navbar2.php'; ?>
<br><br><br>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-10">
            <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded shadow-sm">
                <div class="lh-100">
                    <h1 class="mb-0 text-white lh-100">My Favorite Posts</h1>
                </div>
            </div>

            <div class="my-3 p-3 bg-white rounded shadow-sm bordered-div">
                <h5 class="border-bottom border-gray pb-2 mb-0">Favorite Posts List</h5>
                
                <?php
                $sqlPosts = "SELECT p.post_id, p.post_content, t.topic_subject, t.topic_id, u.username, u.userImg, 
                            p.post_date, c.cat_name, p.post_votes
                            FROM favorites f 
                            INNER JOIN posts p ON f.post_id = p.post_id 
                            INNER JOIN topics t ON p.post_topic = t.topic_id 
                            INNER JOIN users u ON p.post_by = u.id 
                            INNER JOIN categories c ON t.topic_cat = c.cat_id
                            WHERE f.user_id = ? AND f.post_id IS NOT NULL
                            ORDER BY p.post_date DESC";
                
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sqlPosts)) {
                    die('SQL error');
                } else {
                    mysqli_stmt_bind_param($stmt, "i", $_SESSION['userId']);
                    mysqli_stmt_execute($stmt);
                    $resultPosts = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($resultPosts) > 0) {
                        while ($row = mysqli_fetch_assoc($resultPosts)) {
                            echo '<div class="media text-muted pt-3 favorite-item">
                                    <img src="' . ($row['userImg'] ? $row['userImg'] : 'assets/forum.jpg') . '" 
                                         alt="' . $row['username'] . '" class="mr-2 rounded" style="width:48px;height:48px;">
                                    <div class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <strong class="text-gray-dark">
                                                <a href="posts.php?topic=' . $row['topic_id'] . '" style="color: #01497C;">' 
                                                . htmlspecialchars($row['topic_subject']) . '</a>
                                            </strong>
                                            <a href="includes/remove_favorite.inc.php?post_id=' . $row['post_id'] . '" 
                                               class="btn btn-danger btn-sm">Remove</a>
                                        </div>
                                        <span class="d-block">' . substr(htmlspecialchars($row['post_content']), 0, 200) . '...</span>
                                        <div class="d-flex justify-content-between mt-2">
                                            <small class="text-muted">
                                                Posted by ' . htmlspecialchars($row['username']) . ' | ' 
                                                . date("F j, Y", strtotime($row['post_date'])) . '
                                            </small>
                                            <small>
                                                Category: ' . htmlspecialchars($row['cat_name']) . ' | 
                                                Votes: ' . $row['post_votes'] . '
                                            </small>
                                        </div>
                                    </div>
                                  </div>';
                        }
                    } else {
                        echo '<div class="text-center py-5">
                                <i class="fas fa-bookmark fa-4x text-muted mb-4"></i>
                                <h4>No favorite posts yet</h4>
                                <p>You haven\'t added any posts to your favorites yet.</p>
                                <a href="home.php" class="btn btn-custom">Browse Posts</a>
                              </div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<style>
    .bg-purple {
        background-color: #014F86;
    }
    
    .bordered-div {
        border: 1px solid #013A63;
    }
    
    .favorite-item {
        transition: all 0.3s ease;
    }
    
    .favorite-item:hover {
        background-color: #f8f9fa;
    }
    
    .btn-custom {
        background-color: #01497C;
        color: white;
        border: none;
        border-radius: 20px;
        padding: 8px 16px;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }
    
    .btn-custom:hover {
        background-color: #013A63;
        color: white;
    }
    
    .favorite-item .media-body {
        position: relative;
    }
    
    .favorite-item .btn-danger {
        position: absolute;
        top: 0;
        right: 0;
        border-radius: 20px;
        padding: 5px 10px;
        font-size: 12px;
    }
</style>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>