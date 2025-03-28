<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

include 'includes/HTML-head.php';
?>

<link rel="stylesheet" type="text/css" href="css/comp-creation.css">
</head>

<body>

    <?php include 'includes/navbar2.php'; ?>
    <br><br><br><br>

    <div class="container-contact2">
        <div class="wrap-contact2">
            <form class="contact2-form" action="includes/create-event.inc.php" method="post" enctype="multipart/form-data">

                <img class="cover-img" id="blah" src="#">
                <br><br><br>
                <span class="contact2-form-title" style="color: #010101;">Create an Event</span>
                <span class="text-center">
                    <?php
                    if (isset($_GET['error'])) {
                        if ($_GET['error'] == 'emptyfields') {
                            echo '<h5 class="text-danger">*Fill In All The Fields</h5>';
                        } else if ($_GET['error'] == 'catnametaken') {
                            echo '<h5 class="text-danger">*A category with the given name already exists</h5>';
                        } else if ($_GET['error'] == 'sqlerror') {
                            echo '<h5 class="text-danger">*Website Error: Contact admin to have the issue fixed</h5>';
                        }
                    } else if (isset($_GET['creation']) && $_GET['creation'] == 'success') {
                        echo '<h5 class="text-success">*Event successfully created</h5>';
                    }
                    ?>
                </span>

                <div class="wrap-input2 validate-input" data-validate="Name is required">
                    <input class="input2" type="text" id="etitle" name="etitle" placeholder="Event Title" style="color: #010101;">
                    <span class="focus-input2"></span>
                </div>

                <div class="wrap-input2 validate-input" data-validate="Name is required">
                    <input class="input2" type="text" id="ehead" name="ehead" placeholder="Event Headline">
                    <span class="focus-input2"></span>
                </div>
                <div class="wrap-input2 validate-input" data-validate="Location is required">
                    <input class="input2" type="text" id="elocation" name="elocation" placeholder="Event Location">
                    <span class="focus-input2"></span>
                </div>
                <br>
                <div class="form-row">
                    
                <div class="d-flex justify-content-center">
                <div class="form-row">
                    <div class="col">
                        <input type="date" id="edate" name="edate" class="form-control" placeholder="Date">
                        <small id="eventdate" class="form-text text-muted">Event Date</small>
                    </div>
                    <div class="col">
                        <input type="time" id="etime" name="etime" class="form-control" placeholder="Time">
                        <small id="eventtime" class="form-text text-muted">Event Time</small>
                    </div>
                    <div class="col">
                        <input type="time" id="endtime" name="endtime" class="form-control" placeholder="End Time">
                        <small id="eventendtime" class="form-text text-muted">Event End Time</small>
                    </div>
                </div>
                </div>
                </div>
                <br></br>
                <div class="col">
                        <h4 class="text-muted" style="color: #010101;">Let's Get Things Going!</h4>
                    </div>
                <br>

                <div class="wrap-input2 validate-input" data-validate="Description is required">
                    <textarea class="input2" id="edescription" name="edescription" rows="5" placeholder="Event Details"></textarea>
                    <span class="focus-input2"></span>
                </div>

                <div class="container-contact2-form-btn">
                    <div class="wrap-contact2-form-btn">
                        <div class="contact2-form-bgbtn"></div>
                        <button class="btn btn-light btn-lg btn-block" type="submit" name="add-event-submit" style="background-color: #014F86; color: #ffffff; padding: 20px 20px; font-size: 15px; width: 150px; line-height: 0.5">
                            Create Event
                        </button>
                    </div>
                </div>

                <div class="text-center">
                    <br><br>
                    <a class="btn btn-light btn-lg" href="events.php" style="background-color: #2C7DA0; color: #ffffff; padding: 15px 15px; font-size: 15px; width: 250px; display: inline-block;">View All Events</a>
                </div>
            </form>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/creation-main.js"></script>

    <script>
        var dp = 'assets/event.jpg';

        $('#blah').attr('src', dp);

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imgInp").change(function() {
            readURL(this);
        });
    </script>

</body>

</html>
