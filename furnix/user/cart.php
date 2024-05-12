
<!doctype html>
<html lang="en">
<head>
<?php include('..\include\head.php');?>
  <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            max-width: 100px;
           
        }
		button {
			position: relative;
			bottom: 100px;
			left: 120px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        /* Hover effect */
        button:hover {
            background-color: #0056b3;
        }
    </style>
	</head>

	<body>

    <?php include('..\include\header.php');?>

		<!-- Start Hero Section -->
			<div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1>Cart</h1>
							</div>
						</div>
						<div class="col-lg-7">
							
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->

		

    <div class="untree_co-section before-footer-section">
    <div class="container">
	<?php
session_start();

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "alhad", "1234", "main");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the product ID and action are set
if (isset($_GET['action']) && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $user_id = $_SESSION['user_id'];

    // If action is add, check if the product already exists in the cart
if ($_GET['action'] == 'add') {
    $sql_check = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $result_check = $conn->query($sql_check);
    
    if ($result_check->num_rows > 0) {
        // If the product already exists, increase its quantity
        $sql_update_quantity = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = '$user_id' AND product_id = '$product_id'";
        if ($conn->query($sql_update_quantity) === TRUE) {
            // Redirect back to shop.php
            header("Location: cart.php");
            exit;
        } else {
            echo "Error updating quantity: " . $conn->error;
        }
    } else {
        // If the product does not exist, insert it into the cart
        $sql_insert = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '1')";
        if ($conn->query($sql_insert) === TRUE) {
            // Redirect back to shop.php
            header("Location: cart.php");
            exit;
        } else {
            echo "Error inserting product into cart: " . $conn->error;
        }
    }
}

    // If action is increase, increase the quantity of the product in the cart
    if ($_GET['action'] == 'increase') {
        $sql = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = '$user_id' AND product_id = '$product_id'";
        if ($conn->query($sql) === TRUE) {
            // Redirect back to cart.php
            header("Location: cart.php");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // If action is decrease, decrease the quantity of the product in the cart
    if ($_GET['action'] == 'decrease') {
        $sql = "UPDATE cart SET quantity = quantity - 1 WHERE user_id = '$user_id' AND product_id = '$product_id'";
        if ($conn->query($sql) === TRUE) {
            // Redirect back to cart.php
            header("Location: cart.php");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // If action is remove, remove the product from the cart
    if ($_GET['action'] == 'remove') {
        $sql = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
        if ($conn->query($sql) === TRUE) {
            // Redirect back to cart.php
            header("Location: cart.php");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}


// Fetch cart items for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT cart.*, products.product_name, products.product_price, products.product_image FROM cart 
        INNER JOIN products ON cart.product_id = products.product_id 
        WHERE cart.user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Product</th><th>name</th><th>Quantity</th><th>Price</th><th>Actions</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
		echo "<td><img src='" . $row['product_image'] . "' alt='Product Image'></td>";
        echo "<td>" . $row['product_name'] . "</td>";
        echo "<td>" . $row['quantity'] . "</td>";
        echo "<td>rs" . $row['product_price'] * $row['quantity'] . "</td>";
        echo "<td>";
        echo "<a href='cart.php?action=increase&product_id=" . $row['product_id'] . "'>Increase</a> | ";
        echo "<a href='cart.php?action=decrease&product_id=" . $row['product_id'] . "'>Decrease</a> | ";
        echo "<a href='cart.php?action=remove&product_id=" . $row['product_id'] . "'>Remove</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Your cart is empty.";
}

// Close database connection
$conn->close();
?>
    </div>
    </div>
	<a href="checkout.php?user_id=<?php echo $user_id; ?>"><button>Proceed to Checkout</button></a>
        <?php include('..\include\footer.php');?>

		<script src="js/bootstrap.bundle.min.js"></script>
		<script src="js/tiny-slider.js"></script>
		<script src="js/custom.js"></script>

	</body>

</html>
