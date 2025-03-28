<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['key']) && isset($_SESSION['cart'][$_GET['key']])) {
    unset($_SESSION['cart'][$_GET['key']]);
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex
}

header("Location: cart.php");
exit();
?>