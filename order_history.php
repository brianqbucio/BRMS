<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['userId'];

// Get all orders for the user
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    die('SQL error');
}

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$orders_result = mysqli_stmt_get_result($stmt);

include 'includes/HTML-head.php';
?>

<link rel="stylesheet" type="text/css" href="css/list-page.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .order-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        background-color: white;
    }
    .order-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 14px;
    }
    .status-processing {
        background-color: #e7f5ff;
        color: #1864ab;
    }
    .status-shipped {
        background-color: #fff3bf;
        color: #e67700;
    }
    .status-delivered {
        background-color: #ebfbee;
        color: #2b8a3e;
    }
    .status-cancelled {
        background-color: #fff5f5;
        color: #c92a2a;
    }
    .order-item {
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }
    .order-item:last-child {
        border-bottom: none;
    }
    .item-quantity {
        color: #666;
        font-size: 0.9rem;
    }
    .item-price {
        font-weight: bold;
    }
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }
    .empty-state-icon {
        font-size: 4rem;
        color: #adb5bd;
        margin-bottom: 20px;
    }
    .order-date {
        color: #6c757d;
        font-size: 0.9rem;
    }
    .order-total {
        font-weight: bold;
        font-size: 1.1rem;
    }
    .btn-order-action {
        padding: 5px 10px;
        font-size: 0.9rem;
    }
</style>
</head>

<body>
    <?php include 'includes/navbar2.php'; ?>
    <br><br><br>
    <main role="main" class="container">
        <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded shadow-sm">
            <div class="lh-100">
                <h1 class="mb-0 text-white lh-100">My Orders</h1>
                <small>View your order history and track shipments</small>
            </div>
        </div>

        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <?php if (mysqli_num_rows($orders_result) > 0): ?>
                <div class="list-group">
                    <?php while ($order = mysqli_fetch_assoc($orders_result)): 
                        // Get order items with quantities
                        $sql_items = "SELECT * FROM order_items WHERE order_id = ?";
                        $stmt_items = mysqli_stmt_init($conn);
                        mysqli_stmt_prepare($stmt_items, $sql_items);
                        mysqli_stmt_bind_param($stmt_items, "i", $order['order_id']);
                        mysqli_stmt_execute($stmt_items);
                        $items_result = mysqli_stmt_get_result($stmt_items);
                        
                        // Get status from database
                        $status = $order['status'] ?? 'processing';
                        $status_class = 'status-' . $status;
                    ?>
                        <div class="order-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="mb-1">Order #<?= htmlspecialchars($order['order_number']) ?></h5>
                                    <small class="order-date">Placed on <?= date("F j, Y", strtotime($order['created_at'])) ?></small>
                                </div>
                                <div>
                                    <span class="status-badge <?= $status_class ?>">
                                        <?= ucfirst($status) ?>
                                        <?php if ($status == 'processing'): ?>
                                            <i class="fas fa-cog ml-1"></i>
                                        <?php elseif ($status == 'shipped'): ?>
                                            <i class="fas fa-truck ml-1"></i>
                                        <?php elseif ($status == 'delivered'): ?>
                                            <i class="fas fa-check ml-1"></i>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Total</span>
                                    <strong class="order-total">₱<?= number_format($order['total_amount'], 2) ?></strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Payment Method</span>
                                    <span><?= ucwords(str_replace('_', ' ', $order['payment_method'])) ?></span>
                                </div>
                            </div>
                            
                            <h6>Items</h6>
                            <div class="order-items">
                                <?php 
                                $item_count = 0;
                                while ($item = mysqli_fetch_assoc($items_result)): 
                                    $item_count++;
                                    if ($item_count > 3) break; // Show only first 3 items
                                ?>
                                    <div class="order-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <span><?= htmlspecialchars($item['product_name']) ?></span>
                                                <span class="item-quantity">Quantity: <?= $item['quantity'] ?></span>
                                            </div>
                                            <span class="item-price">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                                <?php if (mysqli_num_rows($items_result) > 3): ?>
                                    <div class="text-center mt-2">
                                        <small>+<?= mysqli_num_rows($items_result) - 3 ?> more items</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-3 d-flex justify-content-between">
                                <a href="order_details.php?order_id=<?= $order['order_id'] ?>" class="btn btn-sm btn-outline-primary btn-order-action">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                                <?php if ($status == 'shipped'): ?>
                                    <button class="btn btn-sm btn-outline-success btn-order-action" data-toggle="modal" data-target="#trackOrderModal">
                                        <i class="fas fa-truck"></i> Track
                                    </button>
                                <?php endif; ?>
                              
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h4>No orders yet</h4>
                    <p class="text-muted">You haven't placed any orders yet. Start shopping to see your orders here.</p>
                    <a href="home.php" class="btn btn-custom">Start Shopping</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Track Order Modal -->
    <div class="modal fade" id="trackOrderModal" tabindex="-1" role="dialog" aria-labelledby="trackOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="trackOrderModalLabel">Track Your Order</h5>
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
                        <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 75%"></div>
                    </div>
                    <div class="tracking-details">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipped on:</span>
                            <strong><?= date("M d, Y") ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Estimated delivery:</span>
                            <strong><?= date("M d, Y", strtotime("+2 days")) ?></strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Tracking number:</span>
                            <strong>FC-<?= strtoupper(substr(md5(uniqid()), 0, 10)) ?></strong>
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
</body>
</html>