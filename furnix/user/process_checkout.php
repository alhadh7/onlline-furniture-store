<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect to login page if user is not logged in
        header("Location: login.php");
        exit;
    }
    
    // Include database connection
 // Database connection
$conn = new mysqli("localhost", "alhad", "1234", "main");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


    // Get form data
    $user_id = $_SESSION['user_id'];
    $shipping_name = $_POST['name'];
    $shipping_email = $_POST['email'];
    $shipping_phone = $_POST['phone'];
    $shipping_address = $_POST['address'];
    $shipping_state = $_POST['state']; // If you have added state to the form
    $shipping_pincode = $_POST['pincode']; // If you have added pincode to the form
    $payment_method = $_POST['payment_method'];
    $total_price = $_POST['total_price']; // This value should be passed from the form

    // Insert order into orders table
    $insert_order_query = "INSERT INTO orders (user_id, total_price, shipping_name, shipping_email, shipping_phone, shipping_address, shipping_state, shipping_pincode, payment_method)
                           VALUES ('$user_id', '$total_price', '$shipping_name', '$shipping_email', '$shipping_phone', '$shipping_address', '$shipping_state', '$shipping_pincode', '$payment_method')";
    $result = $conn->query($insert_order_query);

    if ($result) {
        // Get the last inserted order id
        $order_id = $conn->insert_id;

        // Fetch cart items from the database
        $fetch_cart_query = "SELECT * FROM cart WHERE user_id = '$user_id'";
        $cart_result = $conn->query($fetch_cart_query);

        // Insert cart items into order_items table
        while ($row = $cart_result->fetch_assoc()) {
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];
            $price = $row['price'];

            // Insert order item
            $insert_order_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price)
                                        VALUES ('$order_id', '$product_id', '$quantity', '$price')";
            $conn->query($insert_order_item_query);
        }

        // Clear the cart after placing the order
        $clear_cart_query = "DELETE FROM cart WHERE user_id = '$user_id'";
        $conn->query($clear_cart_query);

        // Redirect to a success page
        header("Location: checkout_success.php");
        exit;
    } else {
        // If the order insertion fails, handle the error
        echo "Error: " . $conn->error;
    }

    // Close database connection
    $conn->close();
} else {
    // If the form is not submitted, redirect to the checkout page
    header("Location: checkout.php");
    exit;
}