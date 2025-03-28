<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

include 'includes/HTML-head.php';
?>

<link rel="stylesheet" type="text/css" href="css/list-page.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
<style>
    .bordered-div {
        border: 2px solid #013A63;
        border-radius: 10px;
    }
    .cart-item {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .cart-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .cart-item img {
        max-width: 100px;
        border-radius: 10px;
    }
    .btn-outline-primary {
        color: #007bff;
        border-color: #007bff;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.5;
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    .btn-outline-primary:hover {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }
    .btn-danger {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.5;
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    .btn-danger:hover {
        color: #fff;
        background-color: #c82333;
        border-color: #bd2130;
    }
    .btn-custom {
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 20px;
        padding: 8px 16px;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }
    .btn-custom:hover {
        background-color: #0069d9;
        color: white;
    }
    .checkbox-container {
        display: flex;
        align-items: center;
        margin-right: 15px;
    }
    .checkbox-container input[type="checkbox"] {
        margin-right: 10px;
    }
    .quantity-control {
        display: flex;
        align-items: center;
        margin: 10px 0;
    }
    .quantity-control input {
        width: 60px;
        text-align: center;
        margin: 0 5px;
    }
    .quantity-btn {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        user-select: none;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
    }
    .quantity-btn:hover {
        background-color: #e9ecef;
    }
    .subtotal {
        font-weight: bold;
        color: #01497C;
    }
    .price {
        display: none; /* We'll use data attribute instead */
    }
</style>
</head>

<body>
    <?php include 'includes/navbar2.php'; ?>
    <br><br><br>
    <main role="main" class="container">
        <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded shadow-sm">
            <div class="lh-100">
                <h1 class="mb-0 text-white lh-100">Your Cart</h1>
            </div>
        </div>

        <div class="my-3 p-3 bg-white rounded shadow-sm bordered-div">
            <h5 class="border-bottom border-gray pb-2 mb-0">Cart Items</h5>

            <form action="process_cart.php" method="POST" id="cart-form">
                <?php
                $total = 0;
                if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $key => $item) {
                        $product_id = $item['product_id'];
                        $quantity = $item['quantity'];
                        
                        $sql = "SELECT product_id, product_name, product_description, product_image, price 
                                FROM products 
                                WHERE product_id = ?";
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
                                
                                echo '<div class="media text-muted pt-3 cart-item">
                                        <div class="checkbox-container">
                                            <input type="checkbox" name="selected_items[]" value="' . $key . '" checked>
                                        </div>
                                        <img src="' . $row['product_image'] . '" alt="' . $row['product_name'] . '" class="mr-3 rounded">
                                        <div class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                            <strong class="d-block text-gray-dark">' . ucwords($row['product_name']) . '</strong>
                                            ' . $row['product_description'] . '<br>
                                            <div class="quantity-control">
                                                <span class="quantity-btn minus" data-key="' . $key . '">-</span>
                                                <input type="number" name="quantities[' . $key . ']" value="' . $quantity . '" min="1" class="form-control quantity-input">
                                                <span class="quantity-btn plus" data-key="' . $key . '">+</span>
                                            </div>
                                            <div class="d-flex justify-content-between mt-2">
                                                <span class="text-primary" data-price="' . $row['price'] . '">₱' . number_format($row['price'], 2) . ' each</span>
                                                <span class="subtotal">Subtotal: ₱' . number_format($subtotal, 2) . '</span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <a href="remove_from_cart.php?key=' . $key . '" class="btn btn-danger btn-sm">Remove</a>
                                        </div>
                                      </div>';
                            }
                        }
                    }
                    
                    echo '<div class="text-right mt-3">
                            <h4 class="total-amount">Total: ₱' . number_format($total, 2) . '</h4>
                          </div>';
                } else {
                    echo '<div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                            <h4>Your cart is empty</h4>
                            <p>You haven\'t added any products to your cart yet.</p>
                            <a href="home.php" class="btn btn-custom">Browse Products</a>
                          </div>';
                }
                ?>

                <?php if (!empty($_SESSION['cart'])): ?>
                <div class="d-flex justify-content-between mt-3">
                    <a href="home.php" class="btn btn-outline-primary">Continue Shopping</a>
                    <div>
                        <button type="submit" name="action" value="update" class="btn btn-outline-primary mr-2">Update Cart</button>
                        <button type="submit" name="action" value="checkout" class="btn btn-custom">Proceed to Checkout</button>
                    </div>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        // Function to update subtotals and total
        function updateCartTotals() {
            let grandTotal = 0;
            
            $('.cart-item').each(function() {
                const price = parseFloat($(this).find('.text-primary').data('price'));
                const quantity = parseInt($(this).find('.quantity-input').val());
                const subtotal = price * quantity;
                
                $(this).find('.subtotal').text('Subtotal: ₱' + subtotal.toFixed(2));
                grandTotal += subtotal;
            });
            
            $('.total-amount').text('Total: ₱' + grandTotal.toFixed(2));
        }

        // Quantity controls
        $('.quantity-btn').click(function() {
            const key = $(this).data('key');
            const input = $(this).siblings('.quantity-input');
            let quantity = parseInt(input.val());
            
            if ($(this).hasClass('minus')) {
                if (quantity > 1) {
                    input.val(quantity - 1);
                }
            } else {
                input.val(quantity + 1);
            }
            
            updateCartTotals();
        });
        
        // Update when quantity changes manually
        $('.quantity-input').on('change input', function() {
            updateCartTotals();
        });
        
        // Initialize totals on page load
        updateCartTotals();
        
        // Prevent form submission on quantity buttons
        $('.quantity-btn').click(function(e) {
            e.preventDefault();
        });
    });
    </script>
</body>
</html>