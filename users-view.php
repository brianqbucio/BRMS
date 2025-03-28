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
<style>
    .custom-border {
        border: 2px solid #013A63;
    }
</style>
</head>

<?php include 'includes/navbar2.php'; ?>
<br>
<br>
<br>
<main role="main" class="container">
    <div class="mx-5">
        <div class="d-flex align-items-center p-3 my-3 mx-5 text-white-50 bg-purple rounded shadow-sm">
            <div class="lh-100">
                <h1 class="mb-0 text-white lh-100">Business Users</h1>
            </div>
        </div>

        <div class="my-3 mx-5 p-3 bg-white rounded shadow-sm custom-border">
            <h5 class="border-bottom border-gray pb-2 mb-0">Find People on Business</h5>

            <?php
            $sql = "SELECT id, firstname, middlename, lastname, suffix, birthdate, age, contactnumber, business_name, business_type, address, username, email, userImg  
                    FROM users
                    WHERE id != ?"; // Add condition to exclude current user
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $sql)) {
                die('SQL error: ' . mysqli_error($conn)); // Print SQL error message
            } else {
                mysqli_stmt_bind_param($stmt, "i", $_SESSION['userId']); // Bind session userId to parameter
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                // Check if any results were returned
                if (mysqli_num_rows($result) > 0) {
                    // Display users except the current one
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Check if 'userImg' key exists in the $row array
                        $userImg = isset($row['userImg']) ? $row['userImg'] : 'default.jpg'; // Use a default image if 'userImg' is not set

                        echo '<a href="profile.php?id=' . $row['id'] . '"> <!-- Corrected link to profile.php?id= -->
        <div class="media text-muted pt-3">
            <img src="uploads/'.$userImg.'" alt="" class="mr-2 rounded-circle div-img list-user-img">
            <div class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                <strong class="d-block text-primary">'.ucwords($row['firstname']).'</strong></a> <!-- Closed the <a> tag here -->
                <span class="text-primary">'.ucwords($row['business_name']).'</span><br>
                <span class="text-primary">'.$row['email'].'</span>
            </div>
            <span class="text-right text-primary">
                <a href="message.php?id='.$row['id'].'" >
                    <i class="fa fa-comments-o fa-2x" aria-hidden="true" style="color: #000000;"></i>
                </a>
            </span>
        </div>
    </a>';
                    }
                } else {
                    echo 'No results found.';
                }
            }
            ?>
            <small class="d-block text-right mt-3">
                <a href="profile.php" class="btn btn-primary" style="background-color:#014F86; border-color: #014F86;">Go to Profile</a>
                <a href="message.php" class="btn btn-primary" style="background-color:#2C7DA0; border-color: #2C7DA0;">Go to Inbox</a>
            </small>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>

</html>
