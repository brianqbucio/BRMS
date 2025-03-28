<?php
session_start();
include_once 'includes/dbh.inc.php'; // Include your database connection file

if (!isset($_SESSION['userId'])) {
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

if(isset($_GET['event_id'])) {
    $eventId = $_GET['event_id'];

    // Delete related records from 'reminders' table
    $sqlReminders = "DELETE FROM reminders WHERE event_id = ?";
    $stmtReminders = mysqli_stmt_init($conn);

    if(mysqli_stmt_prepare($stmtReminders, $sqlReminders)) {
        mysqli_stmt_bind_param($stmtReminders, "i", $eventId);
        mysqli_stmt_execute($stmtReminders);
    } else {
        // Error handling if SQL statement for 'reminders' table cannot be prepared
        echo "SQL Error: Unable to delete related records from 'reminders' table.";
        exit();
    }

    // Delete event from 'events' table
    $sqlEvents = "DELETE FROM events WHERE event_id = ?";
    $stmtEvents = mysqli_stmt_init($conn);

    if(mysqli_stmt_prepare($stmtEvents, $sqlEvents)) {
        mysqli_stmt_bind_param($stmtEvents, "i", $eventId);
        mysqli_stmt_execute($stmtEvents);
    } else {
        // Error handling if SQL statement for 'events' table cannot be prepared
        echo "SQL Error: Unable to delete event from 'events' table.";
        exit();
    }

    // Delete event from 'event_info' table
    $sqlInfo = "DELETE FROM event_info WHERE event_id = ?";
    $stmtInfo = mysqli_stmt_init($conn);

    if(mysqli_stmt_prepare($stmtInfo, $sqlInfo)) {
        mysqli_stmt_bind_param($stmtInfo, "i", $eventId);
        mysqli_stmt_execute($stmtInfo);
    } else {
        // Error handling if SQL statement for 'event_info' table cannot be prepared
        echo "SQL Error: Unable to delete event from 'event_info' table.";
        exit();
    }

    // Redirect to my_event.php after deleting event
    header("Location: my_event.php");
    exit();
} else {
    // If event ID is not set, redirect to my_event.php
    header("Location: my_event.php");
    exit();
}
?>
