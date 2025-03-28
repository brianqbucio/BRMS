<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    header("Location: cart.php");
    exit();
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['userId'];

// Get order details
$sql = "SELECT * FROM orders WHERE order_id = ? AND user_id = ?";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: cart.php?error=sqlerror");
    exit();
}

mysqli_stmt_bind_param($stmt, "ii", $order_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    header("Location: cart.php?error=ordernotfound");
    exit();
}

// Get order items with quantities
$sql = "SELECT * FROM order_items WHERE order_id = ?";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: cart.php?error=sqlerror");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$items_result = mysqli_stmt_get_result($stmt);

include 'includes/HTML-head.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - <?php echo htmlspecialchars($order['order_number']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #01497C;
            --primary-dark: #013A63;
            --accent: #2A6F97;
            --light: #E6F2F9;
            --success: #28a745;
            --text: #333;
            --text-light: #777;
            --border: #E1E5EE;
            --bg: #F8FAFC;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }
        
        .confirmation-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border);
            overflow: hidden;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .confirmation-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .confirmation-icon {
            font-size: 5rem;
            color: var(--success);
            margin-bottom: 20px;
            animation: bounce 1s;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-20px);}
            60% {transform: translateY(-10px);}
        }
        
        .confirmation-body {
            padding: 30px;
        }
        
        .order-details {
            margin-bottom: 30px;
        }
        
        .detail-card {
            background: var(--light);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .detail-label {
            color: var(--text-light);
            font-weight: 500;
        }
        
        .detail-value {
            font-weight: 600;
        }
        
        .order-items {
            margin-top: 30px;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid var(--border);
            align-items: center;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .item-name {
            font-weight: 600;
        }
        
        .item-meta {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .item-price {
            font-weight: 600;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            font-size: 1.2rem;
            font-weight: 700;
            border-top: 2px solid var(--border);
            margin-top: 15px;
        }
        
        .btn-custom {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-custom:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(1, 73, 124, 0.3);
            color: white;
        }
        
        .btn-outline-custom {
            color: var(--primary);
            border: 2px solid var(--primary);
            background: transparent;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-outline-custom:hover {
            background: var(--primary);
            color: white;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        
        @media (max-width: 768px) {
            .confirmation-header {
                padding: 20px;
            }
            
            .confirmation-body {
                padding: 20px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn-custom, .btn-outline-custom {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar2.php'; ?>
    <br><br><br>
    
    <div class="container">
        <div class="confirmation-container">
            <div class="confirmation-header">
                <div class="confirmation-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>Thank you for your order!</h2>
                <p>Your order #<?php echo htmlspecialchars($order['order_number']); ?> has been placed successfully.</p>
            </div>
            
            <div class="confirmation-body">
                <div class="order-details">
                    <div class="detail-card">
                        <div class="detail-row">
                            <span class="detail-label">Order Number:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($order['order_number']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Date:</span>
                            <span class="detail-value"><?php echo date("F j, Y g:i A", strtotime($order['created_at'])); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Payment Method:</span>
                            <span class="detail-value"><?php echo ucwords(str_replace('_', ' ', $order['payment_method'])); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Payment Status:</span>
                            <span class="detail-value" style="color: var(--success);"><?php echo ucfirst($order['payment_status']); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="order-items">
                    <h5>Order Summary</h5>
                    <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                        <div class="order-item">
                            <div>
                                <div class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                <div class="item-meta">Quantity: <?php echo $item['quantity']; ?></div>
                            </div>
                            <div class="item-price">
                                ₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    
                    <div class="total-row">
                        <span>Total:</span>
                        <span>₱<?php echo number_format($order['total_amount'], 2); ?></span>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="home.php" class="btn btn-custom">Continue Shopping</a>
                    <a href="order_history.php" class="btn btn-outline-custom">View Order History</a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>