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
</head>

<?php include 'includes/navbar2.php'; ?>
<br>
<br>
<br>
<br>
<main role="main" class="container">
    <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded shadow-sm">
        <div class="lh-100">
            <h1 class="mb-0 text-white lh-100">Business Events</h1>
        </div>
    </div>

    <div class="my-3 p-3 bg-white rounded shadow-sm" style="border: 1px solid #013A63;">
        <h5 class="border-bottom border-gray pb-2 mb-0">All Events</h5>

        <?php
       $sql = "SELECT event_id, event_by, title, event_date, event_time, event_endtime, event_image, location
       FROM events
       ORDER BY event_date DESC";
$stmt = mysqli_stmt_init($conn);    

if (!mysqli_stmt_prepare($stmt, $sql)) {
   die('SQL error');
} else {
   mysqli_stmt_execute($stmt);
   $result = mysqli_stmt_get_result($stmt);

   while ($row = mysqli_fetch_assoc($result)) {
       $currentDateTime = new DateTime();
       $eventStartDateTime = new DateTime($row['event_date'] . ' ' . $row['event_time']);
   
       // Calculate the difference in days
       $interval = $currentDateTime->diff($eventStartDateTime);
       $daysRemaining = $interval->days;
   
       $diff = '';
       if ($currentDateTime < $eventStartDateTime) {
           // Event hasn't started yet
           if ($daysRemaining == 0) {
               $diff = 'Event will start soon';
           } elseif ($daysRemaining == 1) {
               $diff = '1 day remaining';
           } else {
               $diff = $daysRemaining . ' days remaining';
           }
       } else {
           $diff = '<span class="text-success">Event is ongoing</span>';
       }
   
       // Convert military time to 12-hour time format
       $eventStartTime = date("g:i A", strtotime($row['event_time']));
       $eventEndTime = date("g:i A", strtotime($row['event_endtime']));
   
       echo '<a href="event-page.php?id='.$row['event_id'].'">
               <div class="media text-muted pt-3">
                   <img src="uploads/'.$row['event_image'].'" alt="" class="mr-2 rounded div-img">
                   <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                       <strong class="d-block text-gray-dark" style="color: #01497C;">'.ucwords($row['title']).'</strong></a>
                       '.date("F jS, Y", strtotime($row['event_date'])).' - '.$eventStartTime.' to '.$eventEndTime.'<br>
                       Location: '.$row['location'].'<br>
                      
                   </p>
               </div>';
   }
}

       ?>

        <small class="d-block text-right mt-3">
            <a href="create-event.php" class="btn btn-primary">Create an Event</a>
        </small>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>             
</html>
