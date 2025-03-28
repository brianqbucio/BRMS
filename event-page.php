<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$eventId = $_GET['id'];

// Retrieve event details from the database
$sql = "SELECT e.event_date, e.event_time, e.event_endtime, e.title, e.event_image, e.event_by, e.location, 
        i.description, u.username, u.userImg, i.headline AS e_headline
        FROM events e
        INNER JOIN event_info i ON e.event_id = i.event_id
        INNER JOIN users u ON e.event_by = u.id
        WHERE e.event_id = ?";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    die('SQL error');
} else {
    mysqli_stmt_bind_param($stmt, "s", $eventId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
}

// Check if the event exists
if (!$row) {
    header("Location: index.php");
    exit();
}

// Combine date and time for event datetime
$event_date = date_create($row['event_date']);
$event_time = date_create($row['event_time']);
$event_datetime = date_format($event_date, 'Y-m-d') . ' ' . date_format($event_time, 'h:i A');

// Combine date and time for event end datetime
$event_enddate = date_create($row['event_endtime']);
$event_endtime = date_format($event_enddate, 'h:i A');

// Check if the event has already happened
$eventHappening = false;
if (strtotime($event_datetime) <= time()) {
    $eventHappening = true;
}

// Check if reminder is already set for this event
$reminderSet = false;
$isOrganizer = false; // Initialize $isOrganizer
if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    $checkReminderSql = "SELECT * FROM reminders WHERE user_id = ? AND event_id = ?";
    $checkReminderStmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($checkReminderStmt, $checkReminderSql)) {
        mysqli_stmt_bind_param($checkReminderStmt, "ii", $userId, $eventId);
        mysqli_stmt_execute($checkReminderStmt);
        mysqli_stmt_store_result($checkReminderStmt);
        $reminderSet = mysqli_stmt_num_rows($checkReminderStmt) > 0;
    }

    // Check if the current user is the organizer of the event
    $isOrganizer = ($_SESSION['userId'] == $row['event_by']);
}

include 'includes/HTML-head.php';
// Check if reminder status is set
$reminderStatus = isset($_GET['reminder']) ? $_GET['reminder'] : '';

// Display alert message if reminder was successfully set
if ($reminderStatus === 'set_success') {
    echo '<script>alert("Successfully Reminded!");</script>';
}
?>

<link href="css/countdown.css" rel="stylesheet">

</head>
<body>
    <?php include 'includes/navbar2.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-9" id="user-section">
                <!-- Event details -->
                <?php if (!empty($row['event_image'])): ?>
                    <img class="blog-cover" src="uploads/<?php echo $row['event_image']; ?>">
                <?php endif; ?>
                <div class="px-5">
                    <div class="text-center px-5">
                        <br><br><br>
                        <?php if (!empty($row['title'])): ?>
                            <h1><?php echo ucwords($row['title']) ?></h1>
                        <?php endif; ?>
                        <br>
                        <?php if (!empty($row['e_headline'])): ?>
                            <h6 class="text-muted"><?php echo ucwords($row['e_headline']) ?></h6>
                        <?php endif; ?>
                        <br><br><br>
                                            
                        
                        <!-- Event start time -->
                        <?php if (!empty($event_datetime)): ?>
                            <p>Start Time: <?php echo $event_datetime; ?></p>
                        <?php endif; ?>

                        <!-- Event end time -->
                        <?php if (!empty($event_endtime)): ?>
                            <p>End Time: <?php echo $event_endtime; ?></p>
                        <?php endif; ?>

                        <!-- Event countdown -->
                        <h3>Event Countdown</h3>
                        <br>
                        <div class="clock-container">
                            <div class="clock"></div>
                        </div>
                        <div class="message"></div>
                        <!-- Event location -->
                        <?php if (!empty($row['location'])): ?>
                            <p>Location: <?php echo $row['location']; ?></p>
                        <?php endif; ?>
                        <br><br><br>

                        <!-- Event description -->
                        <?php if (!empty($row['description'])): ?>
                            <p class="text-justify"><?php echo $row['description'] ?></p>
                        <?php endif; ?>
                        <br><br>
                        <!-- Event organizer -->
                        <?php if (!empty($row['username'])): ?>
                            <p class="text-muted text-left">Organized By: <?php echo ucwords($row['username']); ?></p>
                        <?php endif; ?>
                       <!-- Remind Me button -->
                        <?php if (!$reminderSet && !$isOrganizer && !$eventHappening): ?>
                            <button id="remindMeBtn" class="btn btn-primary">Remind Me</button>
                        <?php elseif ($isOrganizer): ?>
                            <!-- Organizer message -->
                            <p class="text-muted text-left">You organized this event.</p>
                        <?php elseif ($eventHappening): ?>
                            <!-- Event is happening message -->
                            <p class="text-muted text-left"></p>
                        <?php else: ?>
                            <button class="btn btn-secondary" disabled>Reminded</button>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/flipclock.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Function to disable the Remind Me button
            function disableRemindButton() {
                $('#remindMeBtn').prop('disabled', true).removeClass('btn-primary').addClass('btn-secondary');
            }

            // Add event listener to the "Remind Me" button
            $('#remindMeBtn').click(function(e) {
                e.preventDefault(); // Prevent default form submission behavior

                // Send AJAX request to set reminder
                $.ajax({
                    type: "POST",
                    url: "set-reminder.php",
                    data: { eventId: <?php echo $eventId; ?> },
                    success: function(response) {
                        // Display alert message based on response
                        if (response.trim() === 'Reminder set successfully') {
                            alert('Reminder set successfully!');
                            disableRemindButton(); 
                        } else {
                            alert('Failed to set reminder!');
                        }
                    },
                    error: function() {
                        alert('An error occurred while processing your request. Please try again.');
                    }
                });
            });

            var eventDatetime = new Date("<?php echo $event_datetime; ?>");
            var eventEndtime = new Date("<?php echo $event_endtime; ?>");
            var currentTime = new Date();
            var diffSeconds = Math.floor((eventDatetime - currentTime) / 1000);

            function updateCountdown() {
                if (diffSeconds <= 0) {
                    $('.message').html('<br><h1 class="text-success">The Event is Happening!</h1>');
                } else {
                    var days = Math.floor(diffSeconds / (3600 * 24));
                    var hours = Math.floor((diffSeconds % (3600 * 24)) / 3600);
                    var minutes = Math.floor((diffSeconds % 3600) / 60);
                    var seconds = diffSeconds % 60;

                    $('.clock').html(
                        '<div class="countdown-item"><span class="countdown-value">' + days + '</span> <span class="countdown-label">Days</span></div>' +
                        '<div class="countdown-item"><span class="countdown-value">' + hours + '</span> <span class="countdown-label">Hours</span></div>' +
                        '<div class="countdown-item"><span class="countdown-value">' + minutes + '</span> <span class="countdown-label">Minutes</span></div>' +
                        '<div class="countdown-item"><span class="countdown-value">' + seconds + '</span> <span class="countdown-label">Seconds</span></div>'
                    );

                    setTimeout(updateCountdown, 1000); // Update countdown every second
                    diffSeconds--;

                    if (diffSeconds <= 0) {
                        $('.message').html('<br><h1 class="text-danger">The Event has Ended!</h1>');
                    }
                }
            }   

            updateCountdown(); // Initial call to start countdown
        });
    </script>
</body>
</html>
