<?php
session_start();

// Include database handler
require 'includes/dbh.inc.php';

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    exit('User not logged in');
}

// Check if the event ID is provided in the AJAX request
if (isset($_POST['eventId'])) {
    // Get the event ID from the AJAX request
    $eventId = $_POST['eventId'];

    // Initialize ReminderManager object
    $reminderManager = new ReminderManager($conn);

    // Attempt to set the reminder
    $reminderStatus = $reminderManager->setReminder($_SESSION['userId'], $eventId);

    // Output response based on reminder status
    if ($reminderStatus === ReminderManager::REMINDER_ALREADY_SET) {
        exit('Reminder already set');
    } elseif ($reminderStatus === ReminderManager::REMINDER_SET_SUCCESS) {
        exit('Reminder set successfully');
    } else {
        exit('Failed to set reminder');
    }
} else {
    exit('Event ID not provided');
}

/**
 * Class ReminderManager - Handles reminder operations
 */
class ReminderManager
{
    const REMINDER_ALREADY_SET = 1;
    const REMINDER_SET_SUCCESS = 2;

    private $conn;

    // Constructor
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Set a reminder for the specified user and event
    public function setReminder($userId, $eventId)
    {
        // Check if the user has already set a reminder for this event
        if ($this->isReminderSet($userId, $eventId)) {
            return self::REMINDER_ALREADY_SET;
        }

        // Insert the new reminder into the database
        $sql = "INSERT INTO reminders (user_id, event_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $eventId);
        
        if ($stmt->execute()) {
            return self::REMINDER_SET_SUCCESS;
        } else {
            return false;
        }
    }

    // Check if a reminder is already set for the specified user and event
    private function isReminderSet($userId, $eventId)
    {
        $sql = "SELECT * FROM reminders WHERE user_id = ? AND event_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $eventId);
        $stmt->execute();
        $stmt->store_result();

        return $stmt->num_rows > 0;
    }
}
?>
