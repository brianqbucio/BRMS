<?php
session_start();
include_once 'includes/dbh.inc.php';

function strip_bad_chars($input)
{
    $output = preg_replace("/[^a-zA-Z0-9_-]/", "", $input);
    return $output;
}

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

include 'includes/HTML-head.php';
?>

<link href="css/list-page.css" rel="stylesheet">
<link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css"/>
      <style>
    /* Custom CSS for nav-tabs */
    .nav-tabs .nav-link {
        color: #000; /* Default text color */
    }

    .nav-tabs .nav-link:hover {
        color: #013A63; /* Text color on hover */
    }
</style>
</head>

<body>

<?php include 'includes/navbar2.php'; ?> 
<br>
<br>
<br>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-1">
         
        </div>

        <div class="col-sm-10">
            <div class="text-center p-3">
                <br>
            </div>
            <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="forum-tab" data-toggle="tab" href="#forum" role="tab" aria-controls="forum" aria-selected="true">Recent Forums</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="poll-tab" data-toggle="tab" href="#poll" role="tab" aria-controls="poll" aria-selected="false">Recent Polls</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="event-tab" data-toggle="tab" href="#event" role="tab" aria-controls="event" aria-selected="false">Recent Events</a>
                </li>
            </ul>

            <br>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

            <style>
                /* CSS for search bar container */
                .search-container {
                    display: flex;
                    align-items: center;
                    border-radius: 20px;
                    overflow: hidden;
                    background-color: #f5f5f5; /* Background color for the search bar */
                    border: 1px solid #ccc; /* Border color */
                    transition: border-color 0.3s ease; /* Transition effect for border color */
                }

                /* CSS for search input */
                .search-input {
                    flex: 1;
                    padding: 10px;
                    border: none;
                    background: none;
                    outline: none;
                    font-size: 16px;
                    color: #333; /* Text color */
                }

                /* CSS for search button */
                .search-button {
                    background-color: transparent; /* Button background color */
                    color: #fff; /* Button text color */
                    border: none;
                    padding: 0px 15px;
                    border-radius: 0 10px 10px 0;
                    cursor: pointer;
                    transition: background-color 0.3s ease; /* Transition effect for background color */
                }

                /* CSS for search button hover effect */
                .search-button:hover {
                    background-color: transparent; /* Darker background color on hover */
                }

                /* CSS for magnifying glass icon */
                .search-icon {
                    margin-right: 5px; /* Spacing between icon and text */
                }
                .bg-custom {
        background-color: #014F86; /* Change "yourColor" to the desired color */
    }
            </style>

<div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="forum" role="tabpanel" aria-labelledby="forum-tab">
    <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-custom rounded shadow-sm">
        <div class="lh-100">
            <h1 class="mb-0 text-white lh-100">Forums</h1>
        </div>
        <!-- Search Bar -->
        <form class="form-inline ml-auto">
            <input id="forum-search-input" class="form-control mr-sm-2" type="search" placeholder="Search Forum" aria-label="Search" name="query">
            <!-- Icon for search button -->
            <button class="btn btn-outline-light my-2 my-sm-0 search-button" type="button">
                <i class="fas fa-search search-icon"></i> <!-- Font Awesome search icon -->
            </button>
        </form>
    </div>

    <div class="my-3 p-3 bg-white rounded shadow-sm" style="border: 1px solid #013A63;">
    <?php
    // Forum pagination logic
    $page_forum = isset($_GET['page_forum']) ? intval($_GET['page_forum']) : 1;
    $results_per_page_forum = 6;
    $offset_forum = ($page_forum - 1) * $results_per_page_forum;

    $sql_forum = "SELECT topic_id, topic_subject, topic_date, topic_cat, topic_by, userImg, id, username, cat_name, (
        SELECT COUNT(*)
        FROM comments
        WHERE post_id IN (
            SELECT post_id
            FROM posts
            WHERE post_topic = topics.topic_id AND accepted = 1
        )
    ) AS comment_count
    FROM topics
    JOIN users ON topics.topic_by = users.id
    JOIN categories ON topics.topic_cat = categories.cat_id
    WHERE EXISTS (
        SELECT 1
        FROM posts
        WHERE post_topic = topics.topic_id AND accepted = 1
    )
    ORDER BY topic_id DESC
    LIMIT ?, ?";
    $stmt_forum = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt_forum, $sql_forum)) {
        die('SQL error: ' . mysqli_error($conn)); // Display SQL error if query preparation fails
    } else {
        mysqli_stmt_bind_param($stmt_forum, "ii", $offset_forum, $results_per_page_forum);
        mysqli_stmt_execute($stmt_forum);
        $result_forum = mysqli_stmt_get_result($stmt_forum);

        while ($row_forum = mysqli_fetch_assoc($result_forum)) {
            echo '<a href="posts.php?topic=' . $row_forum['topic_id'] . '">
                <div class="media text-muted pt-3">
                    <img src="assets/forum.jpg" alt="" class="mr-2 rounded div-img poll-img">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <strong class="d-block text-gray-dark" style="color: #01497C;">' . ucwords($row_forum['topic_subject']) . '</strong></a>
                        ' . date("F jS, Y", strtotime($row_forum['topic_date'])) . '<br><br>
                        <span class="text-primary">' . $row_forum['comment_count'] . ' Comments</span>
                    </p>
                    <span class="text-right">
                        <a href="posts.php?topic=' . $row_forum['topic_id'] . '" style="color: #01497C;">Go To Forum</a>
                    </span>
                </div>';
        }
    }
    ?>
</div>


    <!-- Pagination for Forum -->
    <nav aria-label="Forum Pagination">
        <ul class="pagination justify-content-center">
            <?php
            // Forum pagination links
            $sql_forum_count = "SELECT COUNT(*) AS total FROM topics";
            $result_forum_count = mysqli_query($conn, $sql_forum_count);
            $row_forum_count = mysqli_fetch_assoc($result_forum_count);
            $total_pages_forum = ceil($row_forum_count['total'] / $results_per_page_forum);

            for ($i = 1; $i <= $total_pages_forum; $i++) {
                echo '<li class="page-item ';
                if ($i == $page_forum) {
                    echo 'active';
                }
                echo '"><a class="page-link" href="home.php?tab=forum&page_forum=' . $i . '">' . $i . '</a></li>';
            }
            ?>
        </ul>
    </nav>
</div>



                <div class="tab-pane fade" id="poll" role="tabpanel" aria-labelledby="poll-tab">
                <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-custom rounded shadow-sm">
        <div class="lh-100">
            <h1 class="mb-0 text-white lh-100">Polls</h1>
        </div>
        <!-- Search Bar -->
        <form class="form-inline ml-auto">
            <input id="poll-search-input" class="form-control mr-sm-2" type="search" placeholder="Search Poll" aria-label="Search" name="query">
            <!-- Icon for search button -->
            <button class="btn btn-outline-light my-2 my-sm-0 search-button" type="button">
                <i class="fas fa-search search-icon"></i> <!-- Font Awesome search icon -->
            </button>
        </form>
        
    </div>  

    <div class="my-3 p-3 bg-white rounded shadow-sm" style="border: 1px solid #013A63;">
        <?php
        // Polls pagination logic
        $page_poll = isset($_GET['page_poll']) ? intval($_GET['page_poll']) : 1;
        $results_per_page_poll = 6;
        $offset_poll = ($page_poll - 1) * $results_per_page_poll;

        $sql_poll = "SELECT p.id, p.subject, p.created, p.poll_desc, p.locked, (
            SELECT COUNT(*) 
            FROM poll_votes v
            WHERE v.poll_id = p.id
        ) AS votes
        FROM polls p 
        WHERE p.accepted = 1
        ORDER BY votes DESC
        LIMIT ?, ?";
        $stmt_poll = mysqli_stmt_init($conn);    

        if (!mysqli_stmt_prepare($stmt_poll, $sql_poll)) {
            die('SQL error');
        } else {
            mysqli_stmt_bind_param($stmt_poll, "ii", $offset_poll, $results_per_page_poll);
            mysqli_stmt_execute($stmt_poll);
            $result_poll = mysqli_stmt_get_result($stmt_poll);

            while ($row_poll = mysqli_fetch_assoc($result_poll)) {
                echo '<a href="poll.php?poll='.$row_poll['id'].'">
                        <div class="media text-muted pt-3">
                            <img src="assets/poll.jpg" alt="" class="mr-2 rounded div-img poll-img">
                            <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                <strong class="d-block text-gray-dark" style="color: #01497C;">'.ucwords($row_poll['subject']).'</strong></a>
                                '.date("F jS, Y", strtotime($row_poll['created'])).'
                                <br><br>
                                <span class="text-primary">'.$row_poll['votes'].' User(s) have voted</span>
                            </p>
                            <span class="text-right">';
                if($row_poll['locked'] === 1) {
                    echo '<br><b class="small text-muted">[Locked Poll]</b>';
                } else {
                    echo '<br><b class="small text-success">[Open Poll]</b>';
                }
                echo '</span>
                        </div>';
            }
        }
        ?>
    </div>

    <!-- Pagination Links for Polls -->
    <nav aria-label="Poll Pagination">
        <ul class="pagination justify-content-center">
            <?php
            // Polls pagination links
            $sql_poll_count = "SELECT COUNT(*) AS total FROM polls";
            $result_poll_count = mysqli_query($conn, $sql_poll_count);
            $row_poll_count = mysqli_fetch_assoc($result_poll_count);
            $total_pages_poll = ceil($row_poll_count['total'] / $results_per_page_poll);

            for ($i = 1; $i <= $total_pages_poll; $i++) {
                echo '<li class="page-item ';
                if ($i == $page_poll) {
                    echo 'active';
                }
                echo '"><a class="page-link" href="home.php?tab=poll&page_poll='.$i.'">'.$i.'</a></li>';
            }
            ?>
        </ul>
    </nav>
</div>


                <div class="tab-pane fade" id="event" role="tabpanel" aria-labelledby="event-tab">
                <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-custom rounded shadow-sm">
        <div class="lh-100">
            <h1 class="mb-0 text-white lh-100">Events</h1>
        </div>
        <!-- Search Bar -->
        <form class="form-inline ml-auto">
            <input id="event-search-input" class="form-control mr-sm-2" type="search" placeholder="Search Event" aria-label="Search" name="query">
            <!-- Icon for search button -->
            <button class="btn btn-outline-light my-2 my-sm-0 search-button" type="button">
                <i class="fas fa-search search-icon"></i> <!-- Font Awesome search icon -->
            </button>
        </form>
        
    </div>  

    <div class="my-3 p-3 bg-white rounded shadow-sm" style="border: 1px solid #013A63;">
    <?php
// Events pagination logic
$page_event = isset($_GET['page_event']) ? intval($_GET['page_event']) : 1;
$results_per_page_event = 6;
$offset_event = ($page_event - 1) * $results_per_page_event;

$sql_event = "SELECT event_id, event_by, title, event_date, event_time, event_endtime, event_image, location
        FROM events
        WHERE accepted = 1
        ORDER BY event_date ASC
        LIMIT ?, ?";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql_event)) {
    die('SQL error');
} else {
    mysqli_stmt_bind_param($stmt, "ii", $offset_event, $results_per_page_event);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $currentDateTime = new DateTime();
        $eventStartDateTime = new DateTime($row['event_date'] . ' ' . $row['event_time']);

        // Calculate the difference in days
        $interval = $currentDateTime->diff($eventStartDateTime);
        $daysRemaining = $interval->days;

        $diff = '';
        if ($currentDateTime < $eventStartDateTime) {
            // Event hasn't started yet
            if ($daysRemaining == 0) {
                $diff = '';
            } elseif ($daysRemaining == 1) {
                $diff = 'days remaining';
            } else {
                $diff = $daysRemaining . ' days remaining';
            }
        } else {
            $diff = '<span class="text-success"></span>';
        }

        // Convert military time to 12-hour time format
        $eventStartTime = date("g:i A", strtotime($row['event_time']));
        $eventEndTime = date("g:i A", strtotime($row['event_endtime']));

        echo '<a href="event-page.php?id='.$row['event_id'].'">
                <div class="media text-muted pt-3">
                    <img src="uploads/'.$row['event_image'].'" alt="" class="mr-2 rounded div-img">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <strong class="d-block text-gray-dark" style="color: #01497C;">'.ucwords($row['title']).'</strong></a>
                        '.date("F jS, Y", strtotime($row['event_date'])).' - '.$eventStartTime.' to '.$eventEndTime.'<br>
                        Location: '.$row['location'].'<br>
                        '.$diff.'
                    </p>
                </div>';
    }
}
?>

    

    </div>

    <!-- Pagination for Events -->
    <nav aria-label="Event Pagination">
        <ul class="pagination justify-content-center">
            <?php
            // Events pagination links
            $event_sql_count = "SELECT COUNT(*) AS total FROM events";
            $event_result_count = mysqli_query($conn, $event_sql_count);
            $event_row_count = mysqli_fetch_assoc($event_result_count);
            $total_event_pages = ceil($event_row_count['total'] / $results_per_page_event);

            for ($i = 1; $i <= $total_event_pages; $i++) {
                echo '<li class="page-item ';
                if ($i == $page_event) {
                    echo 'active';
                }
                echo '"><a class="page-link" href="home.php?tab=event&page_event='.$i.'">'.$i.'</a></li>';
            }
            ?>
        </ul>
    </nav>
</div>
</div>
</div>
       
    </div>
</div>
<br>
<br><br><br><br><br><br>
<section class="contact" id="contact">
      <div class="social">
      <a href="https://www.facebook.com/profile.php?id=61558052399308"><i class="bx bxl-facebook"></i></a>
        <a href="https://twitter.com/on_gobusiness"><i class="bx bxl-twitter"></i></a>
        <a href="https://www.instagram.com/onthegobusiness9/s"><i class="bx bxl-instagram"></i></a>
      </div>
      <div class="links">
        <a href="home.php">Home</a>
        <a href="message.php">Inbox</a>
        <a href="users-view.php">Users</a>
      </div>
      <p>&#169; BusinessOnTheGo - All Right Reserved.</p>
    </section>
    <!--- Link To Custom Js -->
    <script src="main.js"></script>


<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js" ></script>

<script>
    var myVar;

    function pageLoad() {
        myVar = setTimeout(showPage, 4000);
    }
</script>  

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('#forum-search-input').on('input', function () {
            var _search = $(this).val().toLowerCase().trim();

            // Loop through each forum item
            $('#forum .media').each(function () {
                var _text = $(this).text().toLowerCase().trim();

                // Check if the forum item contains the search query
                if (_text.includes(_search)) {
                    $(this).show(); // Show the forum item
                } else {
                    $(this).hide(); // Hide the forum item if it doesn't match the search
                }
            });
        });
    });
</script>



<script>
    $(document).ready(function () {
        $('#poll-search-input').on('input', function () {
            var _search = $(this).val().toLowerCase();

            // Loop through each poll item
            $('#poll .media').each(function () {
                var _text = $(this).text().toLowerCase();
                _text = _text.trim();

                // Check if the poll item contains the search query
                if (_text.includes(_search)) {
                    $(this).show(); // Show the poll item
                } else {
                    $(this).hide(); // Hide the poll item if it doesn't match the search
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('#event-search-input').on('input', function () {
            var _search = $(this).val().toLowerCase();

            // Loop through each poll item
            $('#event .media').each(function () {
                var _text = $(this).text().toLowerCase();
                _text = _text.trim();

                // Check if the poll item contains the search query
                if (_text.includes(_search)) {
                    $(this).show(); // Show the poll item
                } else {
                    $(this).hide(); // Hide the poll item if it doesn't match the search
                }
            });
        });
    });
</script>


</body>
</html>
