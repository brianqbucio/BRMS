<?php
session_start();
include_once 'includes/dbh.inc.php'; // Include your database connection file
include 'includes/HTML-head.php'; // Include your HTML head file
?>

<body>

<?php include 'includes/navbar2.php'; ?> <!-- Include your navbar file -->
<br>
<br>
<br>

<div class="container">
    <h1 class="my-4">Events Starting Now</h1>
    
    <!-- Display events starting now from database -->
    <div class="row">
        <?php
        $sql = "SELECT e.event_id, e.title, ei.headline, e.event_date, TIME_FORMAT(e.event_time, '%h:%i %p') AS event_time_formatted, TIME_FORMAT(e.event_endtime, '%h:%i %p') AS event_endtime_formatted, e.date_created 
                FROM events e
                INNER JOIN event_info ei ON e.event_id = ei.event_id
                INNER JOIN reminders r ON e.event_id = r.event_id
                WHERE r.user_id = ? AND CONCAT(e.event_date, ' ', e.event_time) <= NOW()";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['userId']);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!empty($result) && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='col-lg-12 mb-4'>";
                echo "<div class='card'>";
                echo "<div class='card-body'>";
                // Ensure event title is wrapped inside anchor tag with href pointing to event-page.php
                echo "<h5 class='card-title'><a href='event-page.php?id={$row['event_id']}'>{$row['title']}</a></h5>";
                echo "<h6 class='card-subtitle mb-2 text-muted'>{$row['headline']}</h6>";
                echo "<p class='card-text'>Event Date: {$row['event_date']} | Time: {$row['event_time_formatted']} - End Time: {$row['event_endtime_formatted']}</p>";
                echo "</div>";
                echo "<div class='card-footer'>";
                echo "<small class='text-muted'>Created: {$row['date_created']}</small>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No events starting now or you haven't set reminders for any events.</p>";
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?> <!-- Include your footer file -->

<!-- Include necessary scripts -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<!-- CSS Styles -->
<style>
    body {
        background-color: #f8f9fa; /* Set background color */
        color: #000; /* Set text color */
    }

    .card {
        transition: all 0.3s;
        border-radius: 10px;
        background-color: #fff; /* Set card background color */
    }

    .card:hover {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    }

    .card-text {
        font-size: 16px;
    }

    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between; /* Align children horizontally */
        align-items: center; /* Align children vertically */
    }

    .card-footer small {
        color: #6c757d;
    }
</style>
</body>
</html>
