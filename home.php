<?php
session_start();
include_once 'includes/dbh.inc.php';

function strip_bad_chars($input)
{
    $output = preg_replace("/[^a-zA-Z0-9_-]/", "", $input);
    return $output;
}

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

include 'includes/HTML-head.php';
?>

<link href="css/list-page.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<!-- Replace the Ant Design includes with these simpler toast notifications -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Custom CSS for nav-tabs */
    .nav-tabs .nav-link {
        color: #000; /* Default text color */
        font-family: 'Poppins', sans-serif;
    }

    .nav-tabs .nav-link:hover {
        color: #013A63; /* Text color on hover */
    }

    /* CSS for search bar container */
    .search-container {
        display: flex;
        align-items: center;
        border-radius: 20px;
        overflow: hidden;
        background-color: #f5f5f5; /* Background color for the search bar */
        border: 1px solid #ccc; /* Border color */
        transition: border-color 0.3s ease; /* Transition effect for border color */
    }

    /* CSS for search input */
    .search-input {
        flex: 1;
        padding: 10px;
        border: none;
        background: none;
        outline: none;
        font-size: 16px;
        color: #333; /* Text color */
    }

    /* CSS for search button */
    .search-button {
        background-color: transparent; /* Button background color */
        color: #fff; /* Button text color */
        border: none;
        padding: 0px 15px;
        border-radius: 0 10px 10px 0;
        cursor: pointer;
        transition: background-color 0.3s ease; /* Transition effect for background color */
    }

    /* CSS for search button hover effect */
    .search-button:hover {
        background-color: transparent; /* Darker background color on hover */
    }

    /* CSS for magnifying glass icon */
    .search-icon {
        margin-right: 5px; /* Spacing between icon and text */
    }

    .bg-custom {
        background-color: #014F86; /* Change "yourColor" to the desired color */
    }

    /* CSS for Brands Section */
    .three-column-layout {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* 3 columns per row */
        gap: 25px; /* Space between items */
        padding: 25px;
    }

    /* Ensure consistent height and width for both Brands and Products */
    .three-column-layout .media {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 20px;
        border: none;
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: linear-gradient(135deg, #ffffff, #f8f9fa);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        position: relative;
        height: 350px; /* Fixed height for consistency */
        width: 100%; /* Ensure full width within the grid */
    }

    .three-column-layout .media img {
        width: 100%;
        max-width: 200px;
        height: 150px; /* Fixed height for images */
        border-radius: 10px;
        margin-bottom: 15px;
        object-fit: cover; /* Ensure images fit nicely */
        transition: transform 0.3s ease;
    }

    .three-column-layout .media:hover img {
        transform: scale(1.05);
    }

    .three-column-layout .media p {
        margin: 0;
        font-size: 14px;
        color: #555;
        font-family: 'Poppins', sans-serif;
    }

    .three-column-layout .media strong {
        font-size: 18px;
        color: #01497C;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .three-column-layout .media a {
        text-decoration: none;
        color: inherit;
    }

    /* Badge for new items */
    .badge-new {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #ff4757;
        color: white;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-family: 'Poppins', sans-serif;
        z-index: 1;
    }

    /* Gradient overlay for cards */
    .three-column-layout .media::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0), rgba(0, 0, 0, 0.1));
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 15px;
    }

    .three-column-layout .media:hover::before {
        opacity: 1;
    }

    /* Button for more details */
    .three-column-layout .media .details-button {
        margin-top: 15px;
        padding: 8px 16px;
        background-color: #01497C;
        color: white;
        border: none;
        border-radius: 20px;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .three-column-layout .media .details-button:hover {
        background-color: #013A63;
    }

    /* Add to Cart button styles */
    .add-to-cart {
        position: absolute;
        bottom: 15px;
        right: 15px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #01497C;
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 2;
    }

    .add-to-cart:hover {
        background-color: #013A63;
        transform: scale(1.1);
    }

    .add-to-cart i {
        font-size: 16px;
    }

    /* Ensure the media div has position relative */
    .three-column-layout .media {
        position: relative;
    }
</style>
</head>

<body>

<?php include 'includes/navbar2.php'; ?> 
<br>
<br>
<br>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-10">
            <div class="text-center p-3">
                <br>
            </div>
            <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="forum-tab" data-toggle="tab" href="#forum" role="tab" aria-controls="forum" aria-selected="true">Recent Forums</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="product-tab" data-toggle="tab" href="#product" role="tab" aria-controls="product" aria-selected="false">Recent Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="brand-tab" data-toggle="tab" href="#brand" role="tab" aria-controls="brand" aria-selected="false">Recent Brands</a>
                </li>
            </ul>

            <br>

            <div class="tab-content" id="myTabContent">
                <!-- Forums Tab -->
                <div class="tab-pane fade show active" id="forum" role="tabpanel" aria-labelledby="forum-tab">
                    <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-custom rounded shadow-sm">
                        <div class="lh-100">
                            <h1 class="mb-0 text-white lh-100">Forums</h1>
                        </div>
                        <!-- Search Bar -->
                        <form class="form-inline ml-auto">
                            <input id="forum-search-input" class="form-control mr-sm-2" type="search" placeholder="Search Forum" aria-label="Search" name="query">
                            <button class="btn btn-outline-light my-2 my-sm-0 search-button" type="button">
                                <i class="fas fa-search search-icon"></i>
                            </button>
                        </form>
                    </div>

                    <div class="my-3 p-3 bg-white rounded shadow-sm" style="border: 1px solid #013A63;">
                        <?php
                        // Forum pagination logic
                        $page_forum = isset($_GET['page_forum']) ? intval($_GET['page_forum']) : 1;
                        $results_per_page_forum = 6;
                        $offset_forum = ($page_forum - 1) * $results_per_page_forum;

                        $sql_forum = "SELECT topic_id, topic_subject, topic_date, topic_cat, topic_by, userImg, id, username, cat_name, (
                            SELECT COUNT(*)
                            FROM comments
                            WHERE post_id IN (
                                SELECT post_id
                                FROM posts
                                WHERE post_topic = topics.topic_id AND accepted = 1
                            )
                        ) AS comment_count
                        FROM topics
                        JOIN users ON topics.topic_by = users.id
                        JOIN categories ON topics.topic_cat = categories.cat_id
                        WHERE EXISTS (
                            SELECT 1
                            FROM posts
                            WHERE post_topic = topics.topic_id AND accepted = 1
                        )
                        ORDER BY topic_id DESC
                        LIMIT ?, ?";
                        $stmt_forum = mysqli_stmt_init($conn);

                        if (!mysqli_stmt_prepare($stmt_forum, $sql_forum)) {
                            die('SQL error: ' . mysqli_error($conn)); // Display SQL error if query preparation fails
                        } else {
                            mysqli_stmt_bind_param($stmt_forum, "ii", $offset_forum, $results_per_page_forum);
                            mysqli_stmt_execute($stmt_forum);
                            $result_forum = mysqli_stmt_get_result($stmt_forum);

                            while ($row_forum = mysqli_fetch_assoc($result_forum)) {
                                $topic_date = new DateTime($row_forum['topic_date']);
                                $current_date = new DateTime();
                                $interval = $current_date->diff($topic_date);
                                $days_diff = $interval->days;

                                $new_badge = ($days_diff <= 20) ? '<span class="badge-new">New</span>' : '';

                                echo '<a href="posts.php?topic=' . $row_forum['topic_id'] . '">
                                        <div class="media text-muted pt-3">
                                            ' . $new_badge . '
                                            <img src="assets/forum.jpg" alt="" class="mr-2 rounded div-img poll-img">
                                            <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                                <strong class="d-block text-gray-dark" style="color: #01497C;">' . ucwords($row_forum['topic_subject']) . '</strong></a>
                                                ' . date("F jS, Y", strtotime($row_forum['topic_date'])) . '<br><br>
                                                <span class="text-primary">' . $row_forum['comment_count'] . ' Comments</span>
                                            </p>
                                            <span class="text-right">
                                                <a href="posts.php?topic=' . $row_forum['topic_id'] . '" style="color: #01497C;">Go To Forum</a>
                                            </span>
                                        </div>';
                            }
                        }
                        ?>
                    </div>

                    <!-- Pagination for Forum -->
                    <nav aria-label="Forum Pagination">
                        <ul class="pagination justify-content-center">
                            <?php
                            // Forum pagination links
                            $sql_forum_count = "SELECT COUNT(*) AS total FROM topics";
                            $result_forum_count = mysqli_query($conn, $sql_forum_count);
                            $row_forum_count = mysqli_fetch_assoc($result_forum_count);
                            $total_pages_forum = ceil($row_forum_count['total'] / $results_per_page_forum);

                            for ($i = 1; $i <= $total_pages_forum; $i++) {
                                echo '<li class="page-item ';
                                if ($i == $page_forum) {
                                    echo 'active';
                                }
                                echo '"><a class="page-link" href="home.php?tab=forum&page_forum=' . $i . '">' . $i . '</a></li>';
                            }
                            ?>
                        </ul>
                    </nav>
                </div>

                <!-- Products Tab -->
                <div class="tab-pane fade" id="product" role="tabpanel" aria-labelledby="product-tab">
                    <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-custom rounded shadow-sm">
                        <div class="lh-100">
                            <h1 class="mb-0 text-white lh-100">Products</h1>
                        </div>
                        <!-- Search Bar -->
                        <form class="form-inline ml-auto">
                            <input id="product-search-input" class="form-control mr-sm-2" type="search" placeholder="Search Product" aria-label="Search" name="query">
                            <button class="btn btn-outline-light my-2 my-sm-0 search-button" type="button">
                                <i class="fas fa-search search-icon"></i>
                            </button>
                        </form>
                    </div>

                    <div class="my-3 p-3 bg-white rounded shadow-sm" style="border: 1px solid #013A63;">
                        <div class="three-column-layout">
                            <?php
                            // Products pagination logic
                            $page_product = isset($_GET['page_product']) ? intval($_GET['page_product']) : 1;
                            $results_per_page_product = 9; // Display 9 products per page (3 rows of 3)
                            $offset_product = ($page_product - 1) * $results_per_page_product;

                            $sql_product = "SELECT p.product_id, p.product_name, p.product_description, p.price, p.product_image, b.brand_name, p.created_at
                                            FROM products p
                                            JOIN brands b ON p.brand_id = b.brand_id
                                            ORDER BY p.created_at DESC
                                            LIMIT ?, ?";
                            $stmt_product = mysqli_stmt_init($conn);

                            if (!mysqli_stmt_prepare($stmt_product, $sql_product)) {
                                die('SQL error');
                            } else {
                                mysqli_stmt_bind_param($stmt_product, "ii", $offset_product, $results_per_page_product);
                                mysqli_stmt_execute($stmt_product);
                                $result_product = mysqli_stmt_get_result($stmt_product);

                                while ($row_product = mysqli_fetch_assoc($result_product)) {
                                    $created_at = new DateTime($row_product['created_at']);
                                    $current_date = new DateTime();
                                    $interval = $current_date->diff($created_at);
                                    $days_diff = $interval->days;
                                
                                    $new_badge = ($days_diff <= 20) ? '<span class="badge-new">New</span>' : '';
                                
                                    echo '<div class="media" style="position: relative; z-index: 1;">
                                            ' . $new_badge . '
                                            <img src="' . htmlspecialchars($row_product['product_image']) . '" 
                                                 alt="' . htmlspecialchars($row_product['product_name']) . '" 
                                                 class="mr-2 rounded div-img">
                                            <p class="media-body pb-3 mb-0 small lh-125">
                                                <strong class="d-block text-gray-dark" style="color: #01497C;">' . 
                                                ucwords(htmlspecialchars($row_product['product_name'])) . '</strong>
                                                Brand: ' . htmlspecialchars($row_product['brand_name']) . '<br>
                                                Price: ₱' . number_format($row_product['price'], 2) . '<br>
                                                ' . date("F jS, Y", strtotime($row_product['created_at'])) . '
                                            </p>
                                            <a href="product.php?id=' . urlencode($row_product['product_id']) . '" 
                                               class="details-button" 
                                               style="position: relative; z-index: 2; display: inline-block;">View Details</a>
                                            <button class="add-to-cart" 
                                                    data-product-id="' . htmlspecialchars($row_product['product_id']) . '"
                                                    title="Add to Cart">
                                                <i class="fas fa-shopping-cart"></i>
                                            </button>
                                          </div>';
                                }
                            }
                                ?>
                                
                        </div>
                    </div>

                    <!-- Pagination for Products -->
                    <nav aria-label="Product Pagination">
                        <ul class="pagination justify-content-center">
                            <?php
                            $sql_product_count = "SELECT COUNT(*) AS total FROM products";
                            $result_product_count = mysqli_query($conn, $sql_product_count);
                            $row_product_count = mysqli_fetch_assoc($result_product_count);
                            $total_pages_product = ceil($row_product_count['total'] / $results_per_page_product);

                            for ($i = 1; $i <= $total_pages_product; $i++) {
                                echo '<li class="page-item ';
                                if ($i == $page_product) {
                                    echo 'active';
                                }
                                echo '"><a class="page-link" href="home.php?tab=product&page_product=' . $i . '">' . $i . '</a></li>';
                            }
                            ?>
                        </ul>
                    </nav>
                </div>

                <!-- Brands Tab -->
                <div class="tab-pane fade" id="brand" role="tabpanel" aria-labelledby="brand-tab">
                    <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-custom rounded shadow-sm">
                        <div class="lh-100">
                            <h1 class="mb-0 text-white lh-100">Brands</h1>
                        </div>
                        <!-- Search Bar -->
                        <form class="form-inline ml-auto">
                            <input id="brand-search-input" class="form-control mr-sm-2" type="search" placeholder="Search Brand" aria-label="Search" name="query">
                            <button class="btn btn-outline-light my-2 my-sm-0 search-button" type="button">
                                <i class="fas fa-search search-icon"></i>
                            </button>
                        </form>
                    </div>

                    <div class="my-3 p-3 bg-white rounded shadow-sm" style="border: 1px solid #013A63;">
                        <div class="three-column-layout">
                            <?php
                            // Brands pagination logic
                            $page_brand = isset($_GET['page_brand']) ? intval($_GET['page_brand']) : 1;
                            $results_per_page_brand = 9; // Display 9 brands per page (3 rows of 3)
                            $offset_brand = ($page_brand - 1) * $results_per_page_brand;

                            $sql_brand = "SELECT brand_id, brand_name, brand_description, brand_logo, created_at
                                          FROM brands
                                          ORDER BY created_at DESC
                                          LIMIT ?, ?";
                            $stmt_brand = mysqli_stmt_init($conn);

                            if (!mysqli_stmt_prepare($stmt_brand, $sql_brand)) {
                                die('SQL error');
                            } else {
                                mysqli_stmt_bind_param($stmt_brand, "ii", $offset_brand, $results_per_page_brand);
                                mysqli_stmt_execute($stmt_brand);
                                $result_brand = mysqli_stmt_get_result($stmt_brand);

                                while ($row_brand = mysqli_fetch_assoc($result_brand)) {
                                    $created_at = new DateTime($row_brand['created_at']);
                                    $current_date = new DateTime();
                                    $interval = $current_date->diff($created_at);
                                    $days_diff = $interval->days;

                                    $new_badge = ($days_diff <= 20) ? '<span class="badge-new">New</span>' : '';
                                    echo '<div class="media" style="height: 150px">
                                        ' . $new_badge . '
                                        <img src="' . $row_brand['brand_logo'] . '" alt="' . $row_brand['brand_name'] . '" class="mr-2 rounded div-img" style="width: auto; height: 100px; object-fit: cover;">
                                    </div>';

                                }
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Pagination for Brands -->
                    <nav aria-label="Brand Pagination">
                        <ul class="pagination justify-content-center">
                            <?php
                            $sql_brand_count = "SELECT COUNT(*) AS total FROM brands";
                            $result_brand_count = mysqli_query($conn, $sql_brand_count);
                            $row_brand_count = mysqli_fetch_assoc($result_brand_count);
                            $total_pages_brand = ceil($row_brand_count['total'] / $results_per_page_brand);

                            for ($i = 1; $i <= $total_pages_brand; $i++) {
                                echo '<li class="page-item ';
                                if ($i == $page_brand) {
                                    echo 'active';
                                }
                                echo '"><a class="page-link" href="home.php?tab=brand&page_brand=' . $i . '">' . $i . '</a></li>';
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<br><br><br><br><br><br><br>
<section class="contact" id="contact">
    <div class="social">
        <a href="https://www.facebook.com/profile.php?id=61558052399308"><i class="bx bxl-facebook"></i></a>
        <a href="https://twitter.com/on_gobusiness"><i class="bx bxl-twitter"></i></a>
        <a href="https://www.instagram.com/onthegobusiness9/s"><i class="bx bxl-instagram"></i></a>
    </div>
    <div class="links">
        <a href="home.php">Home</a>
        <a href="message.php">Inbox</a>
        <a href="users-view.php">Users</a>
    </div>
    <p>&#169; BusinessOnTheGo - All Right Reserved.</p>
</section>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    // Activate the correct tab based on the URL parameter
    document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab');

        if (activeTab) {
            const tabLink = document.querySelector(`#${activeTab}-tab`);
            if (tabLink) {
                new bootstrap.Tab(tabLink).show();
            }
        }

        // Search functionality for Forum
        $('#forum-search-input').on('input', function () {
            var _search = $(this).val().toLowerCase();
            $('#forum .media').each(function () {
                var _text = $(this).text().toLowerCase();
                if (_text.includes(_search)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Search functionality for Products
        $('#product-search-input').on('input', function () {
            var _search = $(this).val().toLowerCase();
            $('#product .media').each(function () {
                var _text = $(this).text().toLowerCase();
                if (_text.includes(_search)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Search functionality for Brands
        $('#brand-search-input').on('input', function () {
            var _search = $(this).val().toLowerCase();
            $('#brand .media').each(function () {
                var _text = $(this).text().toLowerCase();
                if (_text.includes(_search)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Enhanced Add to Cart functionality with Toastify notifications
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const productId = this.getAttribute('data-product-id');
        const button = this;
        const productCard = button.closest('.media');
        const productImage = productCard.querySelector('img').src;
        const productName = productCard.querySelector('strong').textContent;
        
        // Add loading state
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;

        // Send an AJAX request to add the product to the cart
        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}`,
        })
        .then(response => response.json())
        .then(data => {
            // Restore button state
            button.innerHTML = originalHTML;
            button.disabled = false;
            
            if (data.status === 'success') {
                Toastify({
                    text: `${productName} added to cart!`,
                    duration: 3000,
                    close: true,
                    gravity: "bottom",
                    position: "right",
                    backgroundColor: "#4CAF50",
                    stopOnFocus: true,
                    avatar: productImage,
                    onClick: function() {
                        window.location.href = 'cart.php';
                    }
                }).showToast();
                
                // Update cart count in navbar if exists
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    const currentCount = parseInt(cartCount.textContent) || 0;
                    cartCount.textContent = currentCount + 1;
                }
            } else {
                Toastify({
                    text: data.message || 'Failed to add to cart',
                    duration: 3000,
                    close: true,
                    gravity: "bottom",
                    position: "right",
                    backgroundColor: "#f44336",
                    stopOnFocus: true
                }).showToast();
            }
        })
        .catch(() => {
            button.innerHTML = originalHTML;
            button.disabled = false;
            Toastify({
                text: 'An error occurred. Please try again.',
                duration: 3000,
                close: true,
                gravity: "bottom",
                position: "right",
                backgroundColor: "#f44336",
                stopOnFocus: true
            }).showToast();
        });
    });
});
        
    });
</script>

</body>
</html>