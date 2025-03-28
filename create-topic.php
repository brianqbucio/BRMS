<?php
session_start();
include_once 'includes/dbh.inc.php';

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
<br>
    <div class="bg-contact2">
    <div class="container-contact2">
			<div class="wrap-contact2">
				<form class="contact2-form" method="post"  action="includes/create-topic.inc.php">
					<span class="contact2-form-title">
						Create A Forum
					</span>
                                    
                                        <span class="text-center">
                                        <?php
                                            if(isset($_GET['error']))
                                            {
                                                if($_GET['error'] == 'emptyfields')
                                                {
                                                    echo '<h5 class="text-danger">*Fill In All The Fields</h5>';
                                                }
                                                else if ($_GET['error'] == 'sqlerror')
                                                {
                                                    echo '<h5 class="text-danger">*Website Error: Contact admin to have the issue fixed</h5>';
                                                }
                                            }
                                            else if (isset($_GET['operation']) == 'success')
                                            {
                                                echo '<h5 class="text-success">*Forum successfully created</h5>';
                                            }
                                        ?>
                                        </span>
                                    
                                    <?php
                                        $sql = "select cat_id, cat_name from categories;";
                                        $stmt = mysqli_stmt_init($conn);    

                                        if (!mysqli_stmt_prepare($stmt, $sql))
                                        {
                                            die('sql error');
                                        }
                                        else
                                        {
                                            mysqli_stmt_execute($stmt);
                                            $result = mysqli_stmt_get_result($stmt);

                                            if (mysqli_num_rows($result) == 0)
                                            {
                                                echo "<h5 class='text-center text-muted'>You cannot create a topic before the admin creates "
                                                . "some categories</h5>";
                                            }
                                            else
                                            {
                                    ?>
                                    
                                    
                                    <div class="wrap-input2 validate-input" data-validate="Name is required">
                                <input class="input2" type="text" name="topic-subject">
                                <span class="focus-input2" data-placeholder="Forum Subject"></span>
                            </div>

                            <div class="wrap-input2 validate-input" data-validate="Description is required">
                                <textarea class="input2" name="topic-description"></textarea>
                                <span class="focus-input2" data-placeholder="Topic Description"></span>
                            </div>

                            <label>Category</label>
                            <select class="form-control" name="topic-cat">
                                <?php 
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value='.$row['cat_id'].'>' . $row['cat_name'] . '</option>';
                                }
                                ?>
                            </select><br><br>

                            <div class="wrap-input2 validate-input" data-validate="Question is required">
                                <textarea class="input2" name="post-content"></textarea>
                                <span class="focus-input2" data-placeholder="Forum Question"></span>
                            </div>

					<div class="container-contact2-form-btn">
						<div class="wrap-contact2-form-btn">
							<div class="contact2-form-bgbtn"></div>
                                                        <button class="btn btn-light btn-lg btn-block" type="submit" name="create-topic" style="background-color: #014F86; color: #ffffff; padding: 20px 20px; font-size: 15px; width: 150px; line-height: 0.2;">Create Forum</button>
						</div>
					</div>
                                    
                                    
                                    
                                    <?php
                                            }
                                        }
                                    ?>
                                        
                                    <div class="text-center">
                                        <br><br> <a class="btn btn-light btn-lg" href="topics.php" style="background-color: #2C7DA0; color: #ffffff; padding: 15px 15px; font-size: 15px; width: 250px;">View Forums</a>
                                    </div>
				</form>
			</div>
		</div>
	</div>
    
    
        
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
        <script src="js/creation-main.js"></script>
    </body>
</html>