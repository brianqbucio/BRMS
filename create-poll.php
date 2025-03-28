<?php
session_start();
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
<br>
<br>
<div class="bg-contact2" style="background-image: url('img/black-bg.jpg');">
    <div class="container-contact2">
        <div class="wrap-contact2">
            <form class="contact2-form" method="post" action="includes/create-poll.inc.php">
                <span class="contact2-form-title" style="color: #010101;">Create Poll</span>

                <span class="text-center">
                    <?php
                    if (isset($_GET['error'])) {
                        if ($_GET['error'] == 'emptyfields') {
                            echo '<h5 class="text-danger">*Fill In All The Fields</h5>';
                        } else if ($_GET['error'] == 'titletaken') {
                            echo '<h5 class="text-danger">*There is already a poll with this title</h5>';
                        } else if ($_GET['error'] == 'sqlerror') {
                            echo '<h5 class="text-danger">*Website Error: Contact admin to have the issue fixed</h5>';
                        }
                    } else if (isset($_GET['creation']) == 'success') {
                        echo '<h5 class="text-success">*Poll successfully created</h5>';
                    }
                    ?>
                </span>

                <div class="wrap-input2 validate-input" data-validate="Name is required">
                    <input class="input2" type="text" name="title">
                    <span class="focus-input2" data-placeholder="Poll Title"></span>
                </div>

                <div class="checkbox-animated">
                    <input id="checkbox_animated_1" type="checkbox" name="is-locked" value="is-locked">
                    <label for="checkbox_animated_1">
                        <span class="check"></span>
                        <span class="box text-muted"></span>
                        Make the poll Locked
                    </label>
                </div><br>

                <div class="wrap-input2 validate-input" data-validate="Description is required">
                    <textarea class="input2" name="desc"></textarea>
                    <span class="focus-input2" data-placeholder="Poll Description"></span>
                </div>

                <div class="col-sm-4">
                    <label for="option">Poll Options</label>
                    <div class="input_fields_wrap">
                        <button class="add_field_button btn btn-light" style="background-color: #007bff; color: #ffffff; padding: 20px 10px; font-size: 15px; width: 150px; line-height: 0.2; margin-left: -5px;">Add More Fields</button>
                        <div class="wrap-input2">
                            <input class='input2' type="text" name="option[]" id="option" placeholder="poll option">
                        </div>
                        <div class="wrap-input2">
                            <input class='input2' type="text" name="option[]" id="option" placeholder="poll option">
                        </div>
                    </div>
                </div>

                <div class="container-contact2-form-btn">
                    <div class="wrap-contact2-form-btn">
                        <div class="contact2-form-bgbtn"></div>
                        <button class="contact2-form-btn" type="submit" name="poll-submit" style="background-color: #014F86; color: #ffffff; padding: 20px 20px; font-size: 15px; width: 150px; line-height: 0.5">Add Poll</button>
                    </div>
                </div>

                <div class="text-center">
                    <br><br>
                    <a class="btn btn-light btn-lg btn-block" href="poll-view.php" style="background-color: #2C7DA0; color: #ffffff; padding: 15px 15px; font-size: 15px; width: 250px; display: inline-block;">View Polls</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/creation-main.js"></script>
<script>
    $(document).ready(function() {
        var max_fields = 6; 
        var wrapper = $(".input_fields_wrap"); 
        var add_button = $(".add_field_button"); 
        var x = 1; 
        $(add_button).click(function(e) { 
            e.preventDefault();
            if (x < max_fields) { 
                x++; 
                $(wrapper).append('<div class="wrap-input2"><input type="text" name="option[]" placeholder="poll option" id="option"><a href="#" class="remove_field"><i class="fa fa-trash" aria-hidden="true"></i></a></div>'); //add input box
            }
        });
        $(wrapper).on("click", ".remove_field", function(e) { 
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        })
    });
</script>
</body>
</html>