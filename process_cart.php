<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update quantities
    if (isset($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $key => $quantity) {
            if (isset($_SESSION['cart'][$key])) {
                $quantity = intval($quantity);
                if ($quantity > 0) {
                    $_SESSION['cart'][$key]['quantity'] = $quantity;
                } else {
                    unset($_SESSION['cart'][$key]);
                }
            }
        }
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex
    }

    // Handle checkout - only selected items
    if (isset($_POST['action']) && $_POST['action'] === 'checkout') {
        if (!empty($_POST['selected_items'])) {
            $_SESSION['checkout_items'] = [];
            foreach ($_POST['selected_items'] as $key) {
                if (isset($_SESSION['cart'][$key])) {
                    $_SESSION['checkout_items'][] = [
                        'product_id' => $_SESSION['cart'][$key]['product_id'],
                        'quantity' => $_SESSION['cart'][$key]['quantity']
                    ];
                }
            }
            header("Location: checkout.php");
            exit();
        } else {
            header("Location: cart.php?error=noitems");
            exit();
        }
    }
}

header("Location: cart.php");
exit();
?>