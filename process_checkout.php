<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

// Check if items were selected for checkout
if (!isset($_SESSION['checkout_items']) || empty($_SESSION['checkout_items'])) {
    header("Location: cart.php?error=noitems");
    exit();
}

$checkout_items = $_SESSION['checkout_items'];
$user_id = $_SESSION['userId'];
$total = 0;

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Calculate total and verify products
    $products = [];
    foreach ($checkout_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        
        // Get product with FOR UPDATE to lock the row
        $sql = "SELECT product_id, product_name, price, available_quantity 
                FROM products WHERE product_id = ? FOR UPDATE";
        $stmt = mysqli_stmt_init($conn);
        
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("SQL error");
        }
        
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Check if enough quantity is available
            if ($row['available_quantity'] < $quantity) {
                throw new Exception("Not enough stock for product: " . $row['product_name']);
            }
            
            $row['quantity'] = $quantity;
            $products[] = $row;
            $total += ($row['price'] * $quantity);
        } else {
            throw new Exception("Product not found");
        }
    }

    // If no valid products found
    if (empty($products)) {
        throw new Exception("No valid products found");
    }

    // Generate a random order number
    $order_number = 'ORD-' . strtoupper(substr(md5(uniqid()), 0, 8));

    // Process the payment
    $payment_method = $_POST['payment_method'] ?? 'credit_card';
    $payment_status = 'completed';
    $transaction_id = 'TXN-' . strtoupper(substr(md5(uniqid()), 0, 12));

    // Create the order in database
    $sql = "INSERT INTO orders (user_id, order_number, total_amount, payment_method, payment_status, transaction_id) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        throw new Exception("SQL error");
    }

    mysqli_stmt_bind_param($stmt, "isssss", $user_id, $order_number, $total, $payment_method, $payment_status, $transaction_id);
    mysqli_stmt_execute($stmt);
    $order_id = mysqli_insert_id($conn);

    // Add order items and update product quantities
    foreach ($products as $product) {
        // Insert order item
        $sql = "INSERT INTO order_items (order_id, product_id, product_name, price, quantity) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("SQL error");
        }
        
        mysqli_stmt_bind_param($stmt, "iisdi", $order_id, $product['product_id'], 
                                 $product['product_name'], $product['price'], $product['quantity']);
        mysqli_stmt_execute($stmt);
        
        // Update product quantity
        $sql = "UPDATE products SET available_quantity = available_quantity - ? WHERE product_id = ?";
        $stmt = mysqli_stmt_init($conn);
        
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception("SQL error");
        }
        
        mysqli_stmt_bind_param($stmt, "ii", $product['quantity'], $product['product_id']);
        mysqli_stmt_execute($stmt);
    }

    // Remove purchased items from cart
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['checkout_items'] as $item) {
            foreach ($_SESSION['cart'] as $key => $cartItem) {
                if ($cartItem['product_id'] == $item['product_id']) {
                    unset($_SESSION['cart'][$key]);
                }
            }
        }
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex
    }

    // Clear checkout items
    unset($_SESSION['checkout_items']);

    // Commit transaction
    mysqli_commit($conn);

    // Redirect to order confirmation
    header("Location: order_confirmation.php?order_id=" . $order_id);
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($conn);
    header("Location: checkout.php?error=" . urlencode($e->getMessage()));
    exit();
}
?>