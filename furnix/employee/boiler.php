<?php
session_start();

// Check if employee is logged in, redirect to login page if not
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit();
}

// Include database connection file
// Database connection
$conn = new mysqli("localhost", "alhad", "1234", "main");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if delivery status is being updated
if (isset($_POST['delivery_id']) && isset($_POST['status'])) {
    $delivery_id = $_POST['delivery_id'];
    $status = $_POST['status'];

    // Update delivery status in the database
    $sql = "UPDATE delivery SET delivered = '$status' WHERE delivery_id = '$delivery_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Delivery status updated successfully.";
    } else {
        echo "Error updating delivery status: " . $conn->error;
    }
}

// Fetch assigned deliveries for the logged-in employee
$employee_id = $_SESSION['employee_id'];
$sql = "SELECT * FROM delivery WHERE employee_id = '$employee_id'";
$result = $conn->query($sql);

// Display assigned deliveries
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['employee_name']; ?>!</h1>
    <h2>Assigned Deliveries</h2>
    <table>
        <thead>
            <tr>
                <th>Delivery ID</th>
                <th>Order ID</th>
                <th>Delivery Timestamp</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['delivery_id'] . "</td>";
                    echo "<td>" . $row['order_id'] . "</td>";
                    echo "<td>" . $row['delivery_timestamp'] . "</td>";
                    echo "<td>" . ($row['delivered'] == 1 ? 'Delivered' : 'Not Delivered') . "</td>";
                    // Add form to update delivery status
                    echo "<td>";
                    echo "<form action='' method='post'>";
                    echo "<input type='hidden' name='delivery_id' value='" . $row['delivery_id'] . "'>";
                    echo "<select name='status'>";
                    echo "<option value='1'>Delivered</option>";
                    echo "<option value='0'>Not Delivered</option>";
                    echo "</select>";
                    echo "<button type='submit'>Update Status</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No deliveries assigned.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <a href="logout.php">Logout</a>
</body>
</html>