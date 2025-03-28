<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

include 'includes/HTML-head.php';

// Check if a poll vote has been submitted
if (isset($_POST['submit-vote'])) {
    // Get the poll ID and the selected option from the form
    $pollId = $_POST['poll-id'];
    $selectedOption = $_POST['option'];

    // Insert a notification into the database
    $notificationContent = 'User ' . $_SESSION['userId'] . ' has voted on the poll.';
    $sql = "INSERT INTO notifications (user_id, notification_type, notification_content, created_at) 
            VALUES (?, 'Poll Vote', ?, NOW())";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "is", $_SESSION['userId'], $notificationContent);
        if (mysqli_stmt_execute($stmt)) {
            // Notification inserted successfully
            echo "Notification inserted successfully.";
        } else {
            // Notification insertion failed
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // SQL query preparation failed
        echo "SQL error: " . mysqli_stmt_error($stmt);
    }
}

?>

<link rel="stylesheet" type="text/css" href="css/list-page.css">
</head>

<?php include 'includes/navbar2.php'; ?>
<br>
<br>
<br>
<br>
<main role="main" class="container">
    <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded shadow-sm">
        <div class="lh-100">
            <h1 class="mb-0 text-white lh-100">Business Polls</h1>
        </div>
    </div>

    <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h5 class="border-bottom border-gray pb-2 mb-0">All Polls</h5>
        
        <?php
        $sql = "SELECT p.id, p.subject, p.created, p.poll_desc, p.locked, (
                    SELECT COUNT(*) 
                    FROM poll_votes v
                    WHERE v.poll_id = p.id
                ) AS votes
                FROM polls p 
                ORDER BY votes DESC";
        
        $stmt = mysqli_stmt_init($conn);    

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            die('SQL error');
        } else {
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<form method="post" action="polls.php">
                        <input type="hidden" name="poll-id" value="' . $row['id'] . '">
                        <div class="media text-muted pt-3">
                            <img src="assets/poll.jpg" alt="" class="mr-2 rounded div-img poll-img">
                            <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray ">
                                <strong class="d-block text-gray-dark" style="color: black;">'.ucwords($row['subject']).'</strong></a>
                                    '.date("F jS, Y", strtotime($row['created'])).'
                                    <br><br>'.substr($row['subject'],0,50).'...<br>
                                    <span class="text-primary">'.$row['votes'].' User(s) have voted</span>
                            </p>
                            <span class="text-right">';
                
                if ($row['locked'] === 1) {
                    echo '<br><span class="text-warning">[Locked Poll]</span>';
                } else {
                    echo '<br><span class="text-success">[Open Poll]</span>';
                    // Display poll options and submit button only if the poll is open
                    // You need to replace option1, option2, etc. with your actual poll options
                    echo '<input type="radio" name="option" value="option1"> Option 1<br>';
                    echo '<input type="radio" name="option" value="option2"> Option 2<br>';
                    // Add more options as needed
                    echo '<input type="submit" name="submit-vote" value="Vote">';
                }
                
                echo '</span>
                        </div>
                    </form>';
            }
       }
       
       echo '<small class="d-block text-right mt-3">
                <a href="create-poll.php" class="btn btn-primary">Create a Poll</a>
            </small>';
        ?>
    </div>
</main>
    
<?php include 'includes/footer.php'; ?>
    
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
