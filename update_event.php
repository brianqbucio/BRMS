<?php
session_start();
require 'includes/dbh.inc.php'; // Include your database connection file

// Redirect to login page if user is not logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

// Check if event ID is set in the URL
if (!isset($_GET['event_id'])) {
    header("Location: my_event.php");
    exit();
}

$eventId = $_GET['event_id'];

// Fetch event data from the database based on event ID
$sql = "SELECT e.title, e.location, ei.headline, ei.description, e.event_date, e.event_time, e.event_endtime 
        FROM events e
        INNER JOIN event_info ei ON e.event_id = ei.event_id
        WHERE e.event_id = ?";

$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $eventId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if event exists
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $title = $row['title']; // Get event title
        $location = $row['location']; // Get event location
        $headline = $row['headline']; // Get event headline
        $description = $row['description']; // Get event description
        $eventDate = $row['event_date']; // Get event date
        $eventTime = $row['event_time']; // Get event time
        $endTime = $row['event_endtime']; // Get end time
    } else {
        // Redirect to my_event.php if event does not exist
        header("Location: my_event.php");
        exit();
    }
} else {
    // Error handling if SQL statement cannot be prepared
    echo "SQL Error: Unable to fetch event data.";
    exit();
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Get updated event details from the form
    $newTitle = $_POST['title'];
    $newLocation = $_POST['location']; // Add location field
    $newHeadline = $_POST['headline'];
    $newDescription = $_POST['description'];
    $newEventDate = $_POST['event_date'];
    $newEventTime = $_POST['event_time'];
    $newEndTime = $_POST['event_endtime'];

    // Update event details in the 'events' table
    $sqlEvents = "UPDATE events 
                  SET title = ?, location = ?, event_date = ?, event_time = ?, event_endtime = ? 
                  WHERE event_id = ?";
    $stmtEvents = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmtEvents, $sqlEvents)) {
        mysqli_stmt_bind_param($stmtEvents, "sssssi", $newTitle, $newLocation, $newEventDate, $newEventTime, $newEndTime, $eventId);
        mysqli_stmt_execute($stmtEvents);
    } else {
        // Error handling if SQL statement for 'events' table cannot be prepared
        echo "SQL Error: Unable to update event in 'events' table.";
        exit();
    }

    // Update event details in the 'event_info' table
    $sqlInfo = "UPDATE event_info SET headline = ?, description = ? WHERE event_id = ?";
    $stmtInfo = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmtInfo, $sqlInfo)) {
        mysqli_stmt_bind_param($stmtInfo, "ssi", $newHeadline, $newDescription, $eventId);
        mysqli_stmt_execute($stmtInfo);
    } else {
        // Error handling if SQL statement for 'event_info' table cannot be prepared
        echo "SQL Error: Unable to update event in 'event_info' table.";
        exit();
    }

    // Redirect to my_event.php after updating event
    header("Location: my_event.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Event</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 60px;
        }

        .container {
            max-width: 800px;
            margin-bottom: 100px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        textarea.form-control {
            height: 200px;
            resize: vertical;
        }

        button[type="submit"] {
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <?php include 'includes/navbar2.php'; ?>

    <div class="container">
        <h1 class="my-4">Update Event</h1>

        <form action="" method="POST">
            <div class="form-group">
                <label for="title">Event Title:</label>
                <input type="text" class="form-control" name="title" id="title" value="<?php echo $title; ?>">
            </div>
            <div class="form-group">
                <label for="headline">Event Headline:</label>
                <input type="text" class="form-control" name="headline" id="headline" value="<?php echo $headline; ?>">
            </div>
            <div class="form-group">
                <label for="location">Location:</label> <!-- Add location field -->
                <input type="text" class="form-control" name="location" id="location" value="<?php echo $location; ?>">
            </div>
            <div class="form-group">
                <label for="description">Event Description:</label>
                <textarea class="form-control" name="description" id="description" rows="8"><?php echo $description; ?></textarea>
            </div>
            <div class="form-group">
                <label for="event_date">Event Date:</label>
                <input type="date" class="form-control" name="event_date" id="event_date" value="<?php echo $eventDate; ?>">
            </div>
            <div class="form-group">
                <label for="event_time">Event Time:</label>
                <input type="time" class="form-control" name="event_time" id="event_time" value="<?php echo $eventTime; ?>">
            </div>
            <div class="form-group">
                <label for="event_endtime">End Time:</label>
                <input type="time" class="form-control" name="event_endtime" id="event_endtime" value="<?php echo $endTime; ?>">
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Update Event</button>
        </form>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>
