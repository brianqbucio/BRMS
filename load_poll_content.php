<?php
session_start();
include_once 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

// Define the number of results per page for polls
$results_per_page_poll = 6;

// Get the current page number from the AJAX request
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the SQL LIMIT clause offset for polls
$offset = ($page - 1) * $results_per_page_poll;

// Query to fetch poll content for the current page
$sql = "SELECT p.id, p.subject, p.created, p.poll_desc, p.locked, (
            SELECT COUNT(*) 
            FROM poll_votes v
            WHERE v.poll_id = p.id
        ) AS votes
        FROM polls p 
        ORDER BY votes DESC
        LIMIT ?, ?";
$stmt = mysqli_stmt_init($conn);    

if (!mysqli_stmt_prepare($stmt, $sql)) {
    die('SQL error');
} else {
    // Bind parameters and execute query
    mysqli_stmt_bind_param($stmt, "ii", $offset, $results_per_page_poll);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Output the poll content
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<a href="poll.php?poll='.$row['id'].'">
                <div class="media text-muted pt-3">
                    <img src="assets/poll.jpg" alt="" class="mr-2 rounded div-img poll-img">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <strong class="d-block text-gray-dark" style="color: #d74a49;">'.ucwords($row['subject']).'</strong></a>
                        '.date("F jS, Y", strtotime($row['created'])).'
                        <br><br>
                        <span class="text-primary">'.$row['votes'].' User(s) have voted</span>
                    </p>
                    <span class="text-right">';
        if($row['locked'] === 1) {
            echo '<br><b class="small text-muted">[Locked Poll]</b>';
        } else {
            echo '<br><b class="small text-success">[Open Poll]</b>';
        }
        echo '</span>
                </div>';
    }
}
?>
