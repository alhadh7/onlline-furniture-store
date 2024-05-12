<?php
               // Include the database connection file if not included already
                // Database connection
                $conn = new mysqli("localhost", "alhad", "1234", "main");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Start session if not started already
                session_start();

                // Check if the user is logged in, if not redirect to login page
                if (!isset($_SESSION['user_id'])) {
                    header("Location: ../ls.php");
                    exit;
                }  
                // Fetch user orders along with order items, product details, and delivery status
                $user_id = $_SESSION['user_id'];
                $sql = "SELECT orders.order_id, orders.order_date, orders.total_price, 
                            products.product_image, products.product_name, order_items.quantity,
                            delivery.delivered
                        FROM orders
                        LEFT JOIN order_items ON orders.order_id = order_items.order_id
                        LEFT JOIN products ON order_items.product_id = products.product_id
                        LEFT JOIN delivery ON orders.order_id = delivery.order_id
                        WHERE orders.user_id = $user_id";
                $result = $conn->query($sql);

                if(isset($_POST['download_bill'])) {
                $orderId = $_POST['order_id'];
    
                // Retrieve order details from the database
                $sql = "SELECT * FROM orders WHERE order_id = $orderId";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $orderDetails = $result->fetch_assoc();
                
                    // Generate bill content
                    $billContent = "Furni The online Furniture Store". "\n";
                    $billContent .= "Order ID: " . $orderDetails['order_id'] . "\n";
                    $billContent .= "Order Date: " . $orderDetails['order_date'] . "\n";
                    // Add more order details as needed
                    $billContent .= "-------------------------------------------------------\n";
                    $billContent .= "Product\t\t\t\tQuantity\tPrice\n";
                    
                    // Fetch order items specifically for generating the bill
                        $sqlItems = "SELECT orders.order_id, orders.order_date, orders.total_price, 
                        products.product_image, products.product_name, order_items.quantity,
                        delivery.delivered
                        FROM orders
                        LEFT JOIN order_items ON orders.order_id = order_items.order_id
                        LEFT JOIN products ON order_items.product_id = products.product_id
                        LEFT JOIN delivery ON orders.order_id = delivery.order_id
                        WHERE orders.user_id = $user_id";
                    $resultItems = $conn->query($sqlItems);

                    if ($resultItems->num_rows > 0) {
                        while ($rowItem = $resultItems->fetch_assoc()) {
                            $billContent .= $rowItem['product_name'] . "\t\t\t" . $rowItem['quantity'] . "\t\t$" . $rowItem['total_price'] . "\n";
                        }
                    }
                    
                    $billContent .= "--------------------------------------------------------\n";

                    // Add total amount to bill content
                    $billContent .= "Total: $" . $orderDetails['total_price'] . "\n";
                    $billContent .= "Thank You For Purchase.Have a good day";
                    // Set HTTP headers for file download
                    header("Content-Disposition: attachment; filename=order_bill_$orderId.txt");
                    header("Content-Type: application/octet-stream");
                    header("Content-Length: " . strlen($billContent));

                    // Output bill content
                    echo $billContent;

                    // Stop further execution
                    exit;
                } else {
                    echo "Order details not found.";
                }
                exit;
            }

                ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('..\include\head.php');?>

</head>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 0px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            height: auto;
        }

        button {
            background-color: #ff0000;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #cc0000;
        }
    </style>
<body>
    <?php include('..\include\header.php');?>
    <div class="untree_co-section product-section before-footer-section">
        <div class="container">
            <div class="row">

               <?php
                if ($result === false) {
                    // Handle query error
                    echo "Error: " . $conn->error;
                } else {
                    if ($result->num_rows > 0) {
                        // Display user orders
                        echo "<h1>Orders</h1>";
                        echo "<table>";
                        echo "<tr><th>Order ID</th><th>Date</th><th>Image</th><th>Product Name</th><th>Price</th><th>Quantity</th><th>Action</th></tr>";
                        while ($row = $result->fetch_assoc()) {
                            $status = ($row['delivered'] == 1) ? 'Delivered' : 'Pending';
                            echo "<tr>";
                            echo "<td>" . $row['order_id'] . "</td>";
                            echo "<td>" . $row['order_date'] . "</td>";
                            echo "<td><img src='" . $row['product_image'] . "' alt='Product Image'></td>";
                            echo "<td>" . $row['product_name'] . "</td>";
                            echo "<td>$" . $row['total_price'] . "</td>";
                            echo "<td>" . $row['quantity'] . "</td>";
                           

                            // Check if the order is undelivered to enable cancellation
                            if ($row['delivered'] == 0) {
                                echo "<td><button onclick='cancelOrder(" . $row['order_id'] . ")'>Cancel Order</button></td>";
                            } else {
                              echo  "<td>Order Delivered <form action='' method='post'><input type='hidden' name='order_id' value='" . $row['order_id'] . "'><input type='submit' name='download_bill' value='invoice'></form></td>" ;
                            }

                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "No orders found.";
                    }
                }

                // Close the database connection
                $conn->close();
                ?>

                <script>
                    function cancelOrder(orderId) {
                        var confirmCancel = confirm("Are you sure you want to cancel this order?");
                        if (confirmCancel) {
                            // Redirect to cancel_order.php with orderId parameter
                            window.location.href = "cancel_order.php?orderId=" + orderId;
                        }
                    }
                </script>

               
            </div>
        </div>
    </div>

    <?php include('..\include\footer.php');?>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/tiny-slider.js"></script>
    <script src="../js/custom.js"></script>
</body>
</html>
