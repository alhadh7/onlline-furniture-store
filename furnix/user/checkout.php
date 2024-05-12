<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('..\include\head.php');?>
    <!-- Add your CSS styles here if needed -->
    <style>
        .containers {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 0px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        form {
            width: 500px;
            padding-left: 50px;
            padding-bottom: 50px;
        }
        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .payment-options {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="containers"></div>
<?php include('..\include\header.php');?>
<h1 style="padding-left: 50px; padding-bottom: 30px; padding-top:30px">Checkout</h1>

<!-- User Information Form -->
<h2 style="padding-left: 50px;">Delivery Information</h2>
<?php
// User information PHP code here
session_start();
// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
// Manual database connectivity
$conn = new mysqli("localhost", "alhad", "1234", "main");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user information from the session or database
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$sql = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_name = $row['username'];
    $user_email = $row['email'];
    $user_phone = $row['phone'];
    $user_address = $row['address'];
    $user_pincode = $row['pincode'];
    $user_state = $row['state'];
} else {
    // If user information is not found, set default values
    $user_name = '';
    $user_email = '';
    $user_phone = '';
    $user_address = '';
    $user_pincode = '';
    $user_state = '';
}
echo "<form action=\"process_checkout.php\" method=\"post\">";
echo "<label for=\"name\">Name:</label>";
echo "<input type=\"text\" id=\"name\" name=\"name\" value=\"$user_name\" required><br><br>";
echo "<label for=\"email\">Email:</label>";
echo "<input type=\"email\" id=\"email\" name=\"email\" value=\"$user_email\" required><br><br>";
echo "<label for=\"phone\">Phone:</label>";
echo "<input type=\"text\" id=\"phone\" name=\"phone\" value=\"$user_phone\" required><br><br>";
echo "<label for=\"address\">Address:</label>";
echo "<textarea id=\"address\" name=\"address\" required>$user_address</textarea><br><br>";
echo "<label for=\"pincode\">Pincode:</label>";
echo "<textarea id=\"pincode\" name=\"pincode\" required>$user_pincode</textarea><br><br>";
echo "<label for=\"state\">State:</label>";
echo "<textarea id=\"state\" name=\"state\" required>$user_state</textarea><br><br>";
?>

<!-- Order Summary -->
<h2>Order Summary</h2>
<?php
// Order summary PHP code here
$total_price = 0; // Initialize total price
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$sql = "SELECT cart.*, products.product_name, products.product_price FROM cart INNER JOIN products ON cart.product_id = products.product_id WHERE cart.user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Product</th><th>Quantity</th><th>Price</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['product_name'] . "</td>";
        echo "<td>" . $row['quantity'] . "</td>";
        echo "<td>$" . $row['product_price'] * $row['quantity'] . "</td>";
        echo "</tr>";
        $total_price += $row['product_price'] * $row['quantity'];
    }
    echo "<input type=\"hidden\" name=\"total_price\" value=\"$total_price\">";
    echo "<tr><td colspan=\"2\"><strong>Total:</strong></td><td><strong>$$total_price</strong></td></tr>";
    echo "</table>";
}
?>

<!-- Payment Information -->
<h2>Payment Information</h2>
<form action="#" method="post">
    <label>Select Payment Method:</label><br><br>
    <!-- Credit Card Payment -->
    <input type="radio" id="credit_card" name="payment_method" value="credit_card" checked>
    <label for="credit_card">Credit Card</label><br><br>
    <div class="credit-card-input">
        <label for="cardNumber">Card Number:</label>
        <input type="text" id="cardNumber" name="cardNumber" maxlength="16" placeholder="Enter your card number" required><br><br>
        <label for="cardHolderName">Cardholder Name:</label>
        <input type="text" id="cardHolderName" name="cardHolderName" placeholder="Enter cardholder's name" required><br><br>
        <label for="expirationDate">Expiration Date:</label>
        <input type="text" id="expirationDate" name="expirationDate" placeholder="MM/YYYY" required><br><br>
        <label for="cvv">CVV:</label>
        <input type="text" id="cvv" name="cvv" minlength="3" maxlength="4" placeholder="Enter CVV" required><br><br>
    </div>

    <!-- Other Payment Methods -->
    <input type="radio" id="paypal" name="payment_method" value="paypal">
    <label for="paypal">PayPal</label><br><br>

    <input type="radio" id="cash_on_delivery" name="payment_method" value="cash_on_delivery">
    <label for="cash_on_delivery">Cash on Delivery</label><br><br>

    <input type="submit" value="Place Order">
</form>

<?php include('..\include\footer.php');?>

<!-- JavaScript for card validation -->
<script>
    document.querySelector('#cardNumber').addEventListener('input', function (e) {
        var input = e.target.value.replace(/\D/g, '').substring(0, 16);
        input = input != '' ? input.match(/.{1,4}/g).join(' ') : '';
        e.target.value = input;
    });

    document.querySelector('#expirationDate').addEventListener('input', function (e) {
        var input = e.target.value.replace(/\D/g, '').substring(0, 6);
        input = input != '' ? input.match(/.{1,2}/g).join('/') : '';
        e.target.value = input;
    });

    document.querySelector('#cvv').addEventListener('input', function (e) {
        var input = e.target.value.replace(/\D/g, '').substring(0, 4);
        e.target.value = input;
    });
</script>
</body>
</html>
