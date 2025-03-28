<?php
session_start();
include_once 'includes/dbh.inc.php';

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

// Get the brand ID from the URL
if (!isset($_GET['id'])) {
    header("Location: home.php");
    exit();
}
$brand_id = intval($_GET['id']);

// Fetch brand details from the database
$sql = "SELECT brand_id, brand_name, brand_description, brand_logo, created_at
        FROM brands
        WHERE brand_id = ?";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    die('SQL error');
} else {
    mysqli_stmt_bind_param($stmt, "i", $brand_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $brand = mysqli_fetch_assoc($result);

    if (!$brand) {
        header("Location: home.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($brand['brand_name']); ?> - BusinessOnTheGo</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/navbar2.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo htmlspecialchars($brand['brand_logo']); ?>" alt="<?php echo htmlspecialchars($brand['brand_name']); ?>" class="img-fluid rounded">
            </div>
            <div class="col-md-6">
                <h1><?php echo htmlspecialchars($brand['brand_name']); ?></h1>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($brand['brand_description']); ?></p>
                <p><strong>Added On:</strong> <?php echo date("F jS, Y", strtotime($brand['created_at'])); ?></p>
                <a href="home.php" class="btn btn-primary">Back to Home</a>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>