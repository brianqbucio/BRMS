<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    header("Location: order_history.php");
    exit();
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['userId'];

// Get order details
$sql = "SELECT * FROM orders WHERE order_id = ? AND user_id = ?";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    die('SQL error');
}

mysqli_stmt_bind_param($stmt, "ii", $order_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    header("Location: order_history.php?error=ordernotfound");
    exit();
}

// Get user details for shipping information
$sql_user = "SELECT firstname, lastname, address, contactnumber FROM users WHERE id = ?";
$stmt_user = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt_user, $sql_user);
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);
$user_result = mysqli_stmt_get_result($stmt_user);
$user = mysqli_fetch_assoc($user_result);

// Get order items with quantities
$sql_items = "SELECT * FROM order_items WHERE order_id = ?";
$stmt_items = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt_items, $sql_items);
mysqli_stmt_bind_param($stmt_items, "i", $order_id);
mysqli_stmt_execute($stmt_items);
$items_result = mysqli_stmt_get_result($stmt_items);
$order_items = mysqli_fetch_all($items_result, MYSQLI_ASSOC);
mysqli_free_result($items_result);

// Calculate subtotal from items (more accurate than using order total)
$subtotal = 0;
foreach ($order_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// Get current status from database
$status = $order['status'];
$status_class = 'status-' . $status;

include 'includes/HTML-head.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - #<?= htmlspecialchars($order['order_number']) ?></title>
    <link rel="stylesheet" type="text/css" href="css/list-page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #01497C;
            --secondary-color: #013A63;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-gray: #f8f9fa;
            --border-color: #e0e0e0;
        }
        
        .order-details-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .order-card {
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            background: white;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 14px;
            gap: 5px;
        }
        
        .status-processing {
            background-color: rgba(1, 73, 124, 0.1);
            color: var(--primary-color);
        }
        
        .status-shipped {
            background-color: rgba(255, 193, 7, 0.2);
            color: #856404;
        }
        
        .status-delivered {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }
        
        .status-cancelled {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }
        
        .order-summary-card {
            background-color: var(--light-gray);
            border-radius: 8px;
            padding: 20px;
            margin-top: 25px;
            border-left: 4px solid var(--primary-color);
        }
        
        .quantity-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            background-color: var(--primary-color);
            color: white;
            font-size: 12px;
            font-weight: 500;
        }
        
        .item-price {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .item-total {
            font-weight: 700;
            color: var(--secondary-color);
        }
        
        .order-item-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            background-color: white;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }
        
        .order-item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        
        .order-header {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .summary-value {
            font-weight: 600;
        }
        
        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-info {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-info:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar2.php'; ?>
    <br><br><br>
    
    <div class="order-details-container">
        <div class="order-card">
            <div class="order-header d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Order #<?= htmlspecialchars($order['order_number']) ?></h2>
                    <p class="text-muted mb-0">Placed on <?= date("F j, Y \a\\t g:i A", strtotime($order['created_at'])) ?></p>
                </div>
                <span class="status-badge <?= $status_class ?>">
                    <?php if ($status == 'processing'): ?>
                        <i class="fas fa-cog"></i>
                    <?php elseif ($status == 'shipped'): ?>
                        <i class="fas fa-truck"></i>
                    <?php elseif ($status == 'delivered'): ?>
                        <i class="fas fa-check-circle"></i>
                    <?php endif; ?>
                    <?= ucfirst($status) ?>
                </span>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="mb-3"><i class="fas fa-receipt mr-2"></i>Order Details</h5>
                    <p><strong>Payment Method:</strong> <?= ucwords(str_replace('_', ' ', $order['payment_method'])) ?></p>
                    <p><strong>Transaction ID:</strong> <?= $order['transaction_id'] ?? 'N/A' ?></p>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-3"><i class="fas fa-truck mr-2"></i>Shipping To</h5>
                    <p><strong>Name:</strong> <?= htmlspecialchars($user['firstname'] . ' ' . htmlspecialchars($user['lastname'])) ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($user['address']) ?></p> </div>
                    <div class="col-md-6"> <p><strong>Contact Number:</strong> <?= htmlspecialchars($user['contactnumber']) ?></p>
                </div>
            </div>

            <h5 class="mb-3"><i class="fas fa-box-open mr-2"></i>Order Items</h5>
            <div class="order-items mb-4">
                <?php foreach ($order_items as $item): 
                    $item_total = $item['price'] * $item['quantity'];
                ?>
                    <div class="order-item-card">
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <span class="quantity-badge"><?= $item['quantity'] ?> ×</span>
                            </div>
                            <div>
                                <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                                <div class="text-muted small">SKU: <?= $item['product_id'] ?></div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="item-price">₱<?= number_format($item['price'], 2) ?> each</div>
                            <div class="item-total">₱<?= number_format($item_total, 2) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="order-summary-card">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fas fa-info-circle mr-2"></i>Order Summary</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td>Subtotal:</td>
                                <td class="text-right summary-value">₱<?= number_format($subtotal, 2) ?></td>
                            </tr>
                            <tr>
                                <td>Shipping Fee:</td>
                                <td class="text-right summary-value">₱<?= number_format($order['shipping_fee'] ?? 0, 2) ?></td>
                            </tr>
                            <?php if (isset($order['discount_amount']) && $order['discount_amount'] > 0): ?>
                            <tr>
                                <td>Discount:</td>
                                <td class="text-right text-danger summary-value">-₱<?= number_format($order['discount_amount'], 2) ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr class="border-top">
                                <td><strong>Total:</strong></td>
                                <td class="text-right"><h4 class="summary-value">₱<?= number_format($order['total_amount'], 2) ?></h4></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="fas fa-history mr-2"></i>Order Timeline</h5>
                        <div class="timeline">
                            <div class="timeline-item <?= in_array($status, ['processing', 'shipped', 'delivered']) ? 'active' : '' ?>">
                                <small class="text-muted"><?= date("M j, Y", strtotime($order['created_at'])) ?></small>
                                <p class="mb-1">Order Placed</p>
                            </div>
                            <div class="timeline-item <?= in_array($status, ['processing', 'shipped', 'delivered']) ? 'active' : '' ?>">
                                <small class="text-muted"><?= date("M j, Y", strtotime($order['created_at'] . ' +1 day')) ?></small>
                                <p class="mb-1">Processing</p>
                            </div>
                            <?php if (in_array($status, ['shipped', 'delivered'])): ?>
                            <div class="timeline-item active">
                                <small class="text-muted"><?= date("M j, Y", strtotime($order['created_at'] . ' +2 days')) ?></small>
                                <p class="mb-1">Shipped</p>
                                <small class="text-muted">Tracking: FC-<?= substr(strtoupper(md5($order_id)), 0, 10) ?></small>
                            </div>
                            <?php endif; ?>
                            <?php if ($status == 'delivered'): ?>
                            <div class="timeline-item active">
                                <small class="text-muted"><?= date("M j, Y", strtotime($order['created_at'] . ' +4 days')) ?></small>
                                <p class="mb-1">Delivered</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="order_history.php" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Orders
                </a>
                <div>
                    <?php if ($status == 'shipped'): ?>
                    <button class="btn btn-outline-secondary mr-2" data-toggle="modal" data-target="#trackOrderModal">
                        <i class="fas fa-truck mr-2"></i> Track Order
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Track Order Modal -->
    <div class="modal fade" id="trackOrderModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Track Your Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-truck fa-3x text-primary"></i>
                        <h4 class="mt-2">Your order is on the way!</h4>
                    </div>
                    <div class="progress mb-4">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             style="width: <?= $status == 'shipped' ? '75%' : '100%' ?>"></div>
                    </div>
                    <div class="tracking-details">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipped on:</span>
                            <strong><?= date("M d, Y", strtotime($order['created_at'] . ' +2 days')) ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Estimated delivery:</span>
                            <strong><?= date("M d, Y", strtotime($order['created_at'] . ' +4 days')) ?></strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Tracking number:</span>
                            <strong>FC-<?= substr(strtoupper(md5($order_id)), 0, 10) ?></strong>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        // Any additional JavaScript can go here
    });
    </script>
</body>
</html>