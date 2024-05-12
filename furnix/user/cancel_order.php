<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Include database connection
// Database connection
$conn = new mysqli("localhost", "alhad", "1234", "main");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if orderId is provided in the URL
if (isset($_GET['orderId'])) {
    $orderId = $_GET['orderId'];

    // Query to delete from order_items table
    $deleteOrderItemsQuery = "DELETE FROM order_items WHERE order_id = $orderId";

    // Query to delete from delivery table
    $deleteDeliveryQuery = "DELETE FROM delivery WHERE order_id = $orderId";

    // Query to delete from orders table
    $deleteOrderQuery = "DELETE FROM orders WHERE order_id = $orderId";

    // Execute the queries
    $conn->query($deleteOrderItemsQuery);
    $conn->query($deleteDeliveryQuery);
    $conn->query($deleteOrderQuery);

    // Redirect to userorders.php with success message
    header("Location: userorders.php?cancelSuccess=true");
    exit;
} else {
    // Redirect to userorders.php with error message if orderId is not provided
    header("Location: userorders.php?error=orderIdNotProvided");
    exit;
}
?>
