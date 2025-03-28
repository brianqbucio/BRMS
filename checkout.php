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

include 'includes/HTML-head.php';
?>

<link rel="stylesheet" type="text/css" href="css/list-page.css">
<style>
    .bordered-div {
        border: 2px solid #013A63;
        border-radius: 10px;
    }
    .order-item {
        padding: 15px;
        border-bottom: 1px solid #eee;
    }
    .payment-method {
        margin: 15px 0;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }
</style>
</head>

<body>
    <?php include 'includes/navbar2.php'; ?>
    <br><br><br>
    <main role="main" class="container">
        <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded shadow-sm">
            <div class="lh-100">
                <h1 class="mb-0 text-white lh-100">Checkout</h1>
            </div>
        </div>

        <form action="process_checkout.php" method="POST">
            <div class="my-3 p-3 bg-white rounded shadow-sm bordered-div">
                <h5 class="border-bottom border-gray pb-2 mb-0">Order Summary</h5>

                <?php
                $total = 0;
                $checkout_items = $_SESSION['checkout_items'];
                
                foreach ($checkout_items as $item) {
                    $product_id = $item['product_id'];
                    $quantity = $item['quantity'];
                    
                    $sql = "SELECT product_name, price FROM products WHERE product_id = ?";
                    $stmt = mysqli_stmt_init($conn);

                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        die('SQL error');
                    } else {
                        mysqli_stmt_bind_param($stmt, "i", $product_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if ($row = mysqli_fetch_assoc($result)) {
                            $subtotal = $row['price'] * $quantity;
                            $total += $subtotal;
                            
                            echo '<div class="order-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>' . ucwords($row['product_name']) . '</strong>
                                            <div>Quantity: ' . $quantity . '</div>
                                        </div>
                                        <div>
                                            <div>₱' . number_format($row['price'], 2) . ' × ' . $quantity . '</div>
                                            <div class="text-right"><strong>₱' . number_format($subtotal, 2) . '</strong></div>
                                        </div>
                                    </div>
                                  </div>';
                        }
                    }
                }
                
                // Add payment method selection
                echo '<div class="payment-method">
                        <h6>Payment Method</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="creditCard" value="credit_card" checked>
                            <label class="form-check-label" for="creditCard">
                                Credit Card
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                            <label class="form-check-label" for="paypal">
                                PayPal
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="bankTransfer" value="bank_transfer">
                            <label class="form-check-label" for="bankTransfer">
                                Bank Transfer
                            </label>
                        </div>
                      </div>';

                echo '<div class="text-right mt-3">
                        <h4>Total: ₱' . number_format($total, 2) . '</h4>
                      </div>';
                ?>

                <div class="d-flex justify-content-between mt-3">
                    <a href="cart.php" class="btn btn-outline-primary">Back to Cart</a>
                    <button type="submit" class="btn btn-primary">Place Order</button>
                </div>
            </div>
        </form>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>