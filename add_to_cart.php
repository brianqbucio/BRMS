<?php
session_start();
require 'includes/dbh.inc.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userId'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in']);
    exit();
}

if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product']);
    exit();
}

$productId = intval($_POST['product_id']);
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if ($quantity < 1) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid quantity']);
    exit();
}

// Check product availability
$sql = "SELECT available_quantity FROM products WHERE product_id = ?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $productId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Product not found']);
    exit();
}

$product = mysqli_fetch_assoc($result);

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Find existing product in cart
$foundKey = null;
foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['product_id'] === $productId) {
        $foundKey = $key;
        break;
    }
}

// Check available quantity
if ($foundKey !== null) {
    $newQuantity = $_SESSION['cart'][$foundKey]['quantity'] + $quantity;
    if ($newQuantity > $product['available_quantity']) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Only ' . $product['available_quantity'] . ' available (already have ' . 
                        $_SESSION['cart'][$foundKey]['quantity'] . ' in cart)'
        ]);
        exit();
    }
    $_SESSION['cart'][$foundKey]['quantity'] = $newQuantity;
} else {
    if ($quantity > $product['available_quantity']) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Only ' . $product['available_quantity'] . ' available'
        ]);
        exit();
    }
    $_SESSION['cart'][] = ['product_id' => $productId, 'quantity' => $quantity];
}

echo json_encode([
    'status' => 'success',
    'message' => 'Product added to cart',
    'cartCount' => array_sum(array_column($_SESSION['cart'], 'quantity'))
]);
?>