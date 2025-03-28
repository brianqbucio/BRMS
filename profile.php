<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$userId = isset($_GET['id']) ? $_GET['id'] : $_SESSION['userId'];

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    die('SQL error: ' . mysqli_error($conn));
} else {
    mysqli_stmt_bind_param($stmt, "s", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
}

include 'includes/HTML-head.php';
?>
<style>
    .custom-background {
    background-color: #ffffff; /* Example background color */
}
</style>
</head>

<body>

    <?php include 'includes/navbar2.php'; ?>
    
          
    </div>
            <div class="col-sm-8 text-center" id="user-section" style="border: 1px solid #013A63;">
              <img class="cover-img" src="img/asd.jpg">
              <img class="profile-img" src="uploads/<?php echo $user['userImg']; ?>">
              <h2><?php echo ucwords($user['username']); ?></h2>
              <h6><?php echo ucwords($user['firstname']) . " " . ucwords($user['lastname']); ?></h6>
              <h6><?php echo '<small class="text-muted">'.$user['email'].'</small>'; ?></h6>
              <br><h6><?php echo $user['business_name']; ?></h6>
              <h6><?php echo $user['address'];?></h6>
              <div class="profile-bio">

              <hr>
             
        
              <h3>Created Forums</h3>
              <br><br>
              
              <?php
                    $sql = "SELECT * FROM topics WHERE topic_by = ?";
                    $stmt = mysqli_stmt_init($conn);    

                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        die('SQL error: ' . mysqli_error($conn)); // Output SQL error
                    } else {
                        mysqli_stmt_bind_param($stmt, "s", $userId); // Use $userId instead of $userid
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        // Check if there are any rows in the result set
                        if (mysqli_num_rows($result) > 0) {
                            echo '<div class="container"><div class="row">';

                            // Loop through each row in the result set
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<div class="col-sm-4" style="padding-bottom: 30px;">
                                        <div class="card user-blogs">
                                            <a href="posts.php?topic='.$row['topic_id'].'">
                                                <img class="card-img-top" src="assets/forum.jpg" alt="Card image cap">
                                                <div class="card-block p-2 custom-background">
                                                    <p class="card-title">'.ucwords($row['topic_subject']).'</p>
                                                    <p class="card-text"><small class="text-muted">'
                                                    .date("F jS, Y", strtotime($row['topic_date'])).'</small></p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>';
                            }

                            echo '</div></div>';
                        } else {
                            // Display a message or placeholder if there are no created forums
                            echo '<div class="container"><div class="row">
                                    <div class="col-sm-4" style="padding-bottom: 30px;"></div>
                                    <div class="col-sm-4">
                                        <img class="profile-empty-img" src="img/empty.png">
                                    </div>
                                    <div class="col-sm-4" style="padding-bottom: 30px;"></div>
                                </div></div>';
                        }
                    }
              ?>
              
              <br><br>
              <hr>
              <h3>Participated Polls</h3>
              <br><br>
              
              
              <?php
                    $sql = "SELECT * FROM poll_votes v 
                            JOIN polls p ON v.poll_id = p.id 
                            JOIN users u ON p.created_by = u.id 
                            WHERE v.vote_by = ?";
                    $stmt = mysqli_stmt_init($conn);    

                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        die('SQL error');
                    } else {
                        mysqli_stmt_bind_param($stmt, "s", $userId); // Use $userId instead of $userid
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        
                        echo '<div class="container">'
                                    .'<div class="row">';
                        
                        // Check if there are any participated polls
                        if(mysqli_num_rows($result) > 0) {
                            // Loop through each participated poll
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<div class="col-sm-4" style="padding-bottom: 30px;">
                                        <div class="card user-blogs">
                                            <a href="poll.php?poll='.$row['poll_id'].'">
                                            <img class="card-img-top" src="assets/poll.jpg" alt="Card image cap">
                                            <div class="card-block p-2 custom-background">
                                              <p class="card-title">'.ucwords($row['subject']).'</p>
                                             <p class="card-text"><small class="text-muted">'
                                             .date("F jS, Y", strtotime($row['created'])).'</small></p>
                                            </div>
                                            </a>
                                          </div>
                                          </div>';
                            }
                        } else {
                            // Display a message or placeholder if there are no participated polls
                            echo '<div class="col-sm-4" style="padding-bottom: 30px;"></div>
                                    <div class="col-sm-4">
                                        <img class="profile-empty-img" src="img/empty.png">
                                      </div>
                                      <div class="col-sm-4" style="padding-bottom: 30px;"></div>';
                        }
                        
                        echo '</div></div>';
                    }
              ?>
              
              
              <br><br>
              
              
              
          </div>
          <div class="col-sm-1">
            
          </div>
        </div>


      </div> <!-- /container -->
      <?php include 'includes/footer.php'; ?>