<?php
session_start();
require 'includes/dbh.inc.php';

// Check if the form is submitted and the required fields are set
if (isset($_POST['conversation_id'], $_POST['user_from'], $_POST['user_to'])) {
    // Retrieve form data
    $conversationId = base64_decode($_POST['conversation_id']);
    $userFrom = base64_decode($_POST['user_from']);
    $userTo = base64_decode($_POST['user_to']);
    $message = isset($_POST['message']) ? $_POST['message'] : null;
    $fileName = null;

    // Check if a file is uploaded
    if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $fileName = $_FILES['file']['name'];
        $tempFile = $_FILES['file']['tmp_name'];
        $targetFile = "uploads/" . $fileName;

        // Move the uploaded file to the target location
        if (move_uploaded_file($tempFile, $targetFile)) {
            // File uploaded successfully
            // If a file is uploaded, set the message to null
            $message = null;
        } else {
            // Handle file upload error
            echo "Failed to move uploaded file";
            exit(); // Stop further execution
        }
    }

    // Insert message into the database
    $sql = "INSERT INTO messages (conversation_id, user_from, user_to, message, file_name) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssss", $conversationId, $userFrom, $userTo, $message, $fileName);
        if (mysqli_stmt_execute($stmt)) {
            // Message inserted successfully
            echo "Message sent successfully";
        } else {
            // Handle SQL execution error
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // Handle SQL error
        echo "SQL error";
    }
} else {
    // Handle form data missing
    echo "Form data missing";
}
?>
