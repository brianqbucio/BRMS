<?php
// Include the database connection file
require 'includes/dbh.inc.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are present
    if (isset($_POST['conversation_id'], $_POST['user_form'], $_POST['user_to'])) {
        // Sanitize and validate input data
        $conversation_id = mysqli_real_escape_string($conn, $_POST['conversation_id']);
        $user_form = mysqli_real_escape_string($conn, $_POST['user_form']);
        $user_to = mysqli_real_escape_string($conn, $_POST['user_to']);

        // Decrypt conversation_id, user_from, and user_to
        $conversation_id = base64_decode($conversation_id);
        $user_form = base64_decode($user_form);
        $user_to = base64_decode($user_to);

        // Check if a file is uploaded
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            // Handle file upload here
            $file = $_FILES['file'];

            // Example: Save the file to a specific directory
            $targetDirectory = 'uploads/';
            $fileName = basename($file['name']);
            $targetPath = $targetDirectory . $fileName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // File uploaded successfully
                // Insert file information into the files table
                $sql = "INSERT INTO files (conversation_id, user_from, user_to, file_path, timestamp) VALUES (?, ?, ?, ?, NOW())";
                $stmt = mysqli_stmt_init($conn);

                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ssss", $conversation_id, $user_form, $user_to, $targetPath);
                    if (mysqli_stmt_execute($stmt)) {
                        echo 'File uploaded successfully.';
                    } else {
                        echo 'Error: Unable to execute SQL statement for file insertion.';
                    }
                } else {
                    echo 'Error: Unable to prepare SQL statement for file insertion.';
                }

                // Close database connection
                mysqli_stmt_close($stmt);
            } else {
                // File upload failed
                echo 'Error: Failed to move uploaded file to target directory.';
            }
        } else {
            // No file uploaded
            echo 'Error: No file uploaded.';
        }
    } else {
        echo 'Error: Incomplete data submitted.';
    }
} else {
    echo 'Error: Invalid request method.';
}
?>
