<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business On the GO!</title>
    <!-- Add your CSS and JavaScript links here -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link href="http://fonts.googleapis.com/css?family=Cookie" rel="stylesheet" type="text/css">
   
    <!-- Add Bootstrap JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>


<style>
   body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        scroll-behavior: smooth; /* Smooth scrolling behavior */
    }
    header {
        background-color: #f8f9fa; /* Navbar background color */
        padding: 10px 0;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000; /* Ensure header stays on top */
        box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Add shadow for depth */
    }
    .navbar {
        display: flex;
        justify-content: center;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .navbar a {
        color: #333;
        text-decoration: none;
        transition: color 0.3s;
        padding: 10px 20px;
        border-radius: 5px;
        position: relative;
    }
    .navbar a.active {
        color: #0056b3; /* Active link text color */
    }
    .navbar a.active::after {
        content: '';
        width: 8px;
        height: 8px;
        background-color: #31363F; /* Dot color */
        border-radius: 50%;
        position: absolute;
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%);
    }
    .logo {
        display: inline-block;
        margin-left: 20px;
        text-decoration: none;
    }
    .logo img {
        height: 40px; /* Adjust logo height */
    }
    .bx-menu {
        display: none; /* Hide menu icon by default */
    }
    #progress-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background-color: #FEFBF6; /* Progress bar color */
        z-index: 9999; /* Ensure it's above other elements */
    }
    
    #progress {
        height: 100%;
        background-color: #0056b3; /* Indicator color */
        width: 0; /* Initially width is 0 */
    }
    @media (max-width: 768px) {
        .navbar {
            align-items: center;
        }
        .navbar li {
            margin-bottom: 10px;
        }
        .bx-menu {
            display: block; /* Show menu icon on smaller screens */
            margin-right: 20px;
            cursor: pointer;
        }
        .navbar a.active::after {
            display: none; /* Hide dot in mobile view */
        }
        
    }
</style>
</head>
<body>
    <!-- Progress Indicator -->
    <div id="progress-container">
        <div id="progress"></div>
    </div>
    
    <header>
        <a href="index.php" class="logo">
            <img src="img/logo.png" alt="Business On The Go Logo">
        </a>
        <div class="bx bx-menu" id="menu-icon"></div>
        <ul class="navbar">
            <li><a class="nav-link" href="index.php">Home</a></li>
            <li><a class="nav-link" href="index.php">About Us</a></li>
            <li><a class="nav-link" href="index.php">Features</a></li>
            <li><a class="nav-link" href="index.php">Why Join?</a></li>
            <li><a class="nav-link" href="index.php">Contact Us</a></li>
            <li><a href="login.php">Sign In</a></li>
        </ul>
    </header>
</body>
</html>
