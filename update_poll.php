<?php
session_start();
include_once 'includes/dbh.inc.php'; // Include your database connection file

if (!isset($_SESSION['userId'])) {
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

// Check if poll ID is set in the URL
if(isset($_GET['poll_id'])) {
    $pollId = $_GET['poll_id'];
    
    // Fetch poll data from the database based on poll ID
    $sql = "SELECT * FROM polls WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);

    if(mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $pollId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        // Check if poll exists
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $pollSubject = $row['subject'];
            $pollDescription = $row['poll_desc'];
        } else {
            // Redirect to my_poll.php if poll does not exist
            header("Location: my_poll.php");
            exit();
        }
    } else {
        // Error handling if SQL statement cannot be prepared
        echo "SQL Error: Unable to fetch poll data.";
        exit();
    }

    // Fetch poll options data from the database based on poll ID
    $sqlOptions = "SELECT * FROM poll_options WHERE poll_id = ?";
    $stmtOptions = mysqli_stmt_init($conn);

    if(mysqli_stmt_prepare($stmtOptions, $sqlOptions)) {
        mysqli_stmt_bind_param($stmtOptions, "i", $pollId);
        mysqli_stmt_execute($stmtOptions);
        $resultOptions = mysqli_stmt_get_result($stmtOptions);
        
        // Check if poll options exist
        if(mysqli_num_rows($resultOptions) > 0) {
            // Store poll options in an array
            $pollOptions = [];
            while ($rowOptions = mysqli_fetch_assoc($resultOptions)) {
                $pollOptions[] = $rowOptions['name'];
            }
        } else {
            // Redirect to my_poll.php if poll options do not exist
            header("Location: my_poll.php");
            exit();
        }
    } else {
        // Error handling if SQL statement cannot be prepared
        echo "SQL Error: Unable to fetch poll options data.";
        exit();
    }
} else {
    // If poll ID is not set, redirect to my_poll.php
    header("Location: my_poll.php");
    exit();
}

// Check if form is submitted
if(isset($_POST['submit'])) {
    // Get updated poll subject and description
    $newPollSubject = $_POST['poll_subject'];
    $newPollDescription = $_POST['poll_description'];

    // Get updated poll options
    $updatedOptions = $_POST['options'];

    // Update poll details in the database
    $updatePollSql = "UPDATE polls SET subject = ?, poll_desc = ? WHERE id = ?";
    $updatePollStmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($updatePollStmt, $updatePollSql)) {
        mysqli_stmt_bind_param($updatePollStmt, "ssi", $newPollSubject, $newPollDescription, $pollId);
        mysqli_stmt_execute($updatePollStmt);
    } else {
        // Error handling if SQL statement cannot be prepared
        echo "SQL Error: Unable to update poll details.";
        exit();
    }

    // Delete existing poll options for this poll ID
    $deleteSql = "DELETE FROM poll_options WHERE poll_id = ?";
    $deleteStmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($deleteStmt, $deleteSql)) {
        mysqli_stmt_bind_param($deleteStmt, "i", $pollId);
        mysqli_stmt_execute($deleteStmt);
    } else {
        // Error handling if SQL statement cannot be prepared
        echo "SQL Error: Unable to delete existing poll options.";
        exit();
    }

    // Insert updated poll options into the database
    foreach ($updatedOptions as $option) {
        $insertSql = "INSERT INTO poll_options (poll_id, name) VALUES (?, ?)";
        $insertStmt = mysqli_stmt_init($conn);
        if(mysqli_stmt_prepare($insertStmt, $insertSql)) {
            mysqli_stmt_bind_param($insertStmt, "is", $pollId, $option);
            mysqli_stmt_execute($insertStmt);
        } else {
            // Error handling if SQL statement cannot be prepared
            echo "SQL Error: Unable to insert updated poll options.";
            exit();
        }
    }

    // Redirect to my_poll.php after updating poll options
    header("Location: my_poll.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Poll</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Set background color */
            padding-top: 60px; /* Add top padding to adjust for fixed navbar */
        }

        .container {
            max-width: 800px;
            margin-bottom: 100px; /* Add bottom margin */
        }

        .form-group {
            margin-bottom: 20px;
        }

        textarea.form-control {
            height: 200px;
            resize: vertical; /* Allow vertical resizing of textarea */
        }

        button[type="submit"] {
            width: 100%;
        }
    </style>
</head>
<body>

<?php include 'includes/navbar2.php'; ?> <!-- Include your navbar2 file -->

<div class="container">
    <h1 class="my-4">Update Poll</h1>

    <form action="" method="POST">
        <div class="form-group">
            <label for="poll_subject">Poll Subject:</label>
            <input type="text" class="form-control" id="poll_subject" name="poll_subject" value="<?php echo $pollSubject; ?>">
        </div>
        <div class="form-group">
            <label for="poll_description">Poll Description:</label>
            <textarea class="form-control" id="poll_description" name="poll_description" rows="4"><?php echo $pollDescription; ?></textarea>
        </div>
        <div class="form-group">
            <label>Poll Options:</label>
            <?php foreach ($pollOptions as $index => $option) : ?>
                <div class="form-group">
                    <input type="text" class="form-control" name="options[]" value="<?php echo $option; ?>">
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Update Poll</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?> <!-- Include your footer file -->

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
