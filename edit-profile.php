<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userId']; // Define $userId

include 'includes/HTML-head.php';

if(isset($_POST['update_profile'])){
    $update_firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $update_lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);
    $update_business_name = mysqli_real_escape_string($conn, $_POST['business_name']);
    $update_address = mysqli_real_escape_string($conn, $_POST['address']);

    mysqli_query($conn, "UPDATE `users` SET firstname = '$update_firstname', lastname = '$update_lastname', email = '$update_email', business_name = '$update_business_name', address = '$update_address' WHERE id = '$userId'") or die('query failed');

    $update_pass = mysqli_real_escape_string($conn, $_POST['new_pass']);
    $confirm_pass = mysqli_real_escape_string($conn, $_POST['confirm_pass']);

    if(!empty($update_pass) || !empty($confirm_pass)){
        if($update_pass !== $confirm_pass){
            $message[] = 'New passwords do not match!';
        } else {
            // Hash new password
            $hashed_password = password_hash($update_pass, PASSWORD_DEFAULT);
            
            // Update password if new password is not empty
            mysqli_query($conn, "UPDATE `users` SET password_hash = '$hashed_password' WHERE id = '$userId'") or die('query failed');
            $message[] = 'Password updated successfully!';
        }
    }

    $update_image = $_FILES['update_image']['name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_folder = 'uploads/' . $update_image; // Define the destination path

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'Image is too large';
        } else {
            $image_update_query = mysqli_query($conn, "UPDATE `users` SET userImg = '$update_image' WHERE id = '$userId'") or die('query failed'); // Changed 'image' to 'userImg'
            if ($image_update_query) {
                move_uploaded_file($update_image_tmp_name, $update_image_folder); // Move the uploaded file to the destination folder
            }
            $message[] = 'Image updated successfully!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Profile</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/profile.css">
</head>
<body>
<?php include 'includes/navbar2.php'; ?>
<div class="update-profile">

   <?php
      $select = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$userId'") or die('query failed');
      if(mysqli_num_rows($select) > 0){
         $fetch = mysqli_fetch_assoc($select);
      }
   ?>

   <form action="" method="post" enctype="multipart/form-data">
      <?php
         if(isset($fetch)){ // Check if $fetch is not null
            if($fetch['userImg'] == ''){
                echo '<img src="uploads/default.jpg">'; // Provide a default image source
            }else{
                echo '<img src="uploads/'.$fetch['userImg'].'">'; // Changed 'uploaded_img' to 'uploads'
            }
            if(isset($message)){
                foreach($message as $msg){
                    echo '<div class="message">'.$msg.'</div>';
                }
            }
         }
      ?>
      <div class="flex">
         <div class="inputBox">
            <span>First Name:</span>
            <input type="text" name="firstname" value="<?php echo $fetch['firstname']; ?>" class="box">
            <span>Last Name:</span>
            <input type="text" name="lastname" value="<?php echo $fetch['lastname']; ?>" class="box">
            <span>Email:</span>
            <input type="email" name="update_email" value="<?php echo $fetch['email']; ?>" class="box">
            <span>Business Name:</span>
            <input type="text" name="business_name" value="<?php echo $fetch['business_name']; ?>" class="box">
           
          
         </div>
         <div class="inputBox">
         <span>Address:</span>
            <input type="text" name="address" value="<?php echo $fetch['address']; ?>" class="box">
         <span>Update Profile Picture:</span>
            <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
            <span>New Password:</span>
            <input type="password" name="new_pass" placeholder="Enter new password" class="box">
            <span>Confirm Password:</span>
            <input type="password" name="confirm_pass" placeholder="Confirm new password" class="box">
         </div>
      </div>
      <input type="submit" value="Update Profile" name="update_profile" class="btn">
      <a href="home.php" class="delete-btn">Go Back</a>
   </form>

</div>

</body>
</html>
