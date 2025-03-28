<?php
session_start();
require 'includes/dbh.inc.php';

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    // If user is not logged in, return an error response
    http_response_code(401); // Unauthorized
    exit();
}

// Check if the poll ID and action are provided in the request
if (!isset($_GET['poll']) || !isset($_GET['action'])) {
    // If poll ID or action is not provided, return an error response
    http_response_code(400); // Bad request
    exit();
}

// Retrieve the poll ID and action from the request
$pollId = $_GET['poll'];
$action = $_GET['action'];

if ($action === 'add') {
    // Insert the poll ID into the user's favorites table in the database
    $sql = "INSERT INTO favorites (user_id, poll_id) VALUES (?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $_SESSION['userId'], $pollId);
        mysqli_stmt_execute($stmt);

        // Return a success response
        http_response_code(200); // OK
        echo "Poll added to favorites successfully";
    } else {
        // If the SQL statement preparation fails, return an error response
        http_response_code(500); // Internal server error
        echo "Error adding poll to favorites: " . mysqli_error($conn);
    }
} elseif ($action === 'remove') {
    // Remove the poll ID from the user's favorites table in the database
    $sql = "DELETE FROM favorites WHERE user_id = ? AND poll_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $_SESSION['userId'], $pollId);
        mysqli_stmt_execute($stmt);

        // Return a success response
        http_response_code(200); // OK
        echo "Poll removed from favorites successfully";
    } else {
        // If the SQL statement preparation fails, return an error response
        http_response_code(500); // Internal server error
        echo "Error removing poll from favorites: " . mysqli_error($conn);
    }
} else {
    // If the action is neither 'add' nor 'remove', return an error response
    http_response_code(400); // Bad request
    echo "Invalid action";
}
?>
