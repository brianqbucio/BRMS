<?php
session_start();
include_once 'includes/dbh.inc.php';

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

// Get the product ID from the URL
if (!isset($_GET['id'])) {
    header("Location: home.php");
    exit();
}
$product_id = intval($_GET['id']);

// Fetch product details from the database
$sql = "SELECT p.product_id, p.product_name, p.product_description, p.product_image, 
               p.price, p.available_quantity, b.brand_name, p.created_at
        FROM products p
        JOIN brands b ON p.brand_id = b.brand_id
        WHERE p.product_id = ?";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    die('SQL error');
} else {
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);

    if (!$product) {
        header("Location: home.php");
        exit();
    }
}

include 'includes/HTML-head.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?> - Product Details</title>
    
    <!-- Include Toastify for notifications -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
    <style>
        :root {
            --primary-color: #01497C;
            --secondary-color: #013A63;
            --accent-color: #2A6F97;
            --light-color: #A9D6E5;
            --danger-color: #e63946;
        }
        
        .bg-purple {
            background-color: var(--primary-color);
        }
        
        .bordered-div {
            border: 1px solid var(--secondary-color);
            border-radius: 8px;
        }
        
        .product-image-container {
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
        }
        
        .product-image {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        
        .product-image:hover {
            transform: scale(1.05);
        }
        
        .product-title {
            color: var(--secondary-color);
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .price-section {
            font-size: 1.8rem;
            margin: 20px 0;
        }
        
        .price {
            color: var(--danger-color);
            font-weight: bold;
        }
        
        .product-description {
            color: #555;
            line-height: 1.8;
            margin-bottom: 25px;
        }
        
        .product-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            color: #666;
            font-size: 0.95rem;
        }
        
        .meta-item i {
            margin-right: 10px;
            color: var(--accent-color);
            font-size: 1.1rem;
        }
        
        .quantity-input {
            width: 80px;
            text-align: center;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .add-to-cart {
            background-color: var(--primary-color);
            border: none;
            padding: 12px 25px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .add-to-cart:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .btn-custom {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 8px 25px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            background-color: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }
        
        .stock-badge {
            font-size: 0.9rem;
            padding: 5px 10px;
            border-radius: 12px;
            margin-left: 10px;
        }
        
        .product-actions {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        
        @media (max-width: 768px) {
            .product-image-container {
                height: 300px;
                margin-bottom: 20px;
            }
            
            .price-section {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
<?php include 'includes/navbar2.php'; ?>
<br><br><br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex align-items-center p-3 my-3 text-white rounded shadow-sm" style="background-color: var(--primary-color);">
                <div class="lh-100">
                    <h1 class="mb-0 text-white lh-100"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                </div>
            </div>

            <div class="my-3 p-3 bg-white rounded shadow-sm bordered-div">
                <div class="row">
                    <!-- Product Image -->
                    <div class="col-md-5">
                        <div class="product-image-container">
                            <img src="<?php echo htmlspecialchars($product['product_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                                 class="product-image">
                        </div>
                    </div>
                    
                    <!-- Product Details -->
                    <div class="col-md-7">
                        <div class="product-details">
                            <h2 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h2>
                            
                            <div class="price-section">
                                <span class="price">₱<?php echo number_format($product['price'], 2); ?></span>
                                <?php if ($product['available_quantity'] > 0): ?>
                                    <span class="badge badge-success stock-badge">In Stock (<?php echo $product['available_quantity']; ?> available)</span>
                                <?php else: ?>
                                    <span class="badge badge-danger stock-badge">Out of Stock</span>
                                <?php endif; ?>
                            </div>
                            
                            <p class="product-description"><?php echo htmlspecialchars($product['product_description']); ?></p>
                            
                            <div class="product-meta">
                                <div class="meta-item">
                                    <i class="fas fa-box-open"></i>
                                    <span>Available Quantity: <?php echo $product['available_quantity']; ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Added on: <?php echo date("F jS, Y", strtotime($product['created_at'])); ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-barcode"></i>
                                    <span>Product ID: <?php echo $product['product_id']; ?></span>
                                </div>
                            </div>
                            
                            <?php if ($product['available_quantity'] > 0): ?>
                            <div class="product-actions">
                                <div class="form-group">
                                    <label for="quantity"><strong>Quantity:</strong></label>
                                    <input type="number" id="quantity" name="quantity" min="1" 
                                           max="<?php echo $product['available_quantity']; ?>" value="1" 
                                           class="form-control quantity-input">
                                </div>
                                <button class="btn btn-primary btn-lg add-to-cart" 
                                        data-product-id="<?php echo $product['product_id']; ?>"
                                        data-product-name="<?php echo htmlspecialchars($product['product_name']); ?>"
                                        data-product-image="<?php echo htmlspecialchars($product['product_image']); ?>">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </div>
                            <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-circle"></i> This product is currently out of stock. Check back later!
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Back button -->
            <div class="text-center mt-4 mb-5">
                <a href="home.php" class="btn btn-custom">
                    <i class="fas fa-arrow-left"></i> Back to Products
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    $('.add-to-cart').click(function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        const productImage = $(this).data('product-image');
        const quantity = $('#quantity').val();
        
        // Disable button and show loading state
        const $button = $(this);
        const originalHtml = $button.html();
        $button.html('<i class="fas fa-spinner fa-spin"></i> Adding...');
        $button.prop('disabled', true);
        
        // Create form data
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', quantity);

        // Send AJAX request
        fetch('add_to_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Toastify({
                    text: `${quantity} × ${productName} added to cart!`,
                    duration: 3000,
                    close: true,
                    gravity: "bottom",
                    position: "right",
                    backgroundColor: "#28a745",
                    avatar: productImage,
                    stopOnFocus: true,
                    onClick: function() {
                        window.location.href = 'cart.php';
                    }
                }).showToast();
                
                // Update cart count
                const cartCount = $('.cart-count');
                if (cartCount.length) {
                    const currentCount = parseInt(cartCount.text()) || 0;
                    cartCount.text(currentCount + parseInt(quantity));
                }
            } else {
                Toastify({
                    text: data.message || 'Failed to add to cart',
                    duration: 3000,
                    close: true,
                    gravity: "bottom",
                    position: "right",
                    backgroundColor: "#dc3545",
                    stopOnFocus: true
                }).showToast();
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            Toastify({
                text: 'Network error. Please try again.',
                duration: 3000,
                close: true,
                gravity: "bottom",
                position: "right",
                backgroundColor: "#dc3545",
                stopOnFocus: true
            }).showToast();
        })
        .finally(() => {
            // Restore button state
            $button.html(originalHtml);
            $button.prop('disabled', false);
        });
    });
});
</script>
</body>
</html>