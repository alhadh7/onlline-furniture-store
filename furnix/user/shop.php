<!DOCTYPE html>
<html lang="en">
<head>
<?php include('..\include\head.php');?>
</head>
<body>
<?php include('..\include\header.php');?>

<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Shop</h1>
                </div>
            </div>
            <div class="col-lg-7">
                
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<div class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row">

            <!-- display_products.php -->
            <?php
            // Include the database connection
            // Connect to the database
            $conn = new mysqli("localhost", "alhad", "1234", "main");
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);

            // Check if there are any products
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    // Modify the link to point to cart.php with product ID and action parameters
                    echo "<div class='col-12 col-md-4 col-lg-3 mb-5'>";
                    echo "<a class='product-item' href='cart.php?action=add&product_id=" . $row['product_id'] . "'>";
                    echo "<img src='" . $row['product_image'] . "' class='img-fluid product-thumbnail'>";
                    echo "<h3 class='product-title'>" . $row['product_name'] . "</h3>";
                    echo "<p class='product-description'>" . $row['product_description'] . "</p>";
                    echo "<strong class='product-price'>₹ " . $row['product_price'] . "</strong>";
                    echo "<span class='icon-cross'>";
                    echo "<img src='../images/cross.svg' class='img-fluid'>";
                    echo "</span>";
                    echo "</a>";
                    echo "</div>";
                }
            } else {
                echo "0 results";
            }
            $conn->close();
            ?>
        </div>
    </div>
</div>

<?php include('..\include\footer.php');?>
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/tiny-slider.js"></script>
<script src="../js/custom.js"></script>
</body>
</html>