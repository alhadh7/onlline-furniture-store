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

// Fetch employee details
$employee_id = $_SESSION['employee_id'];
$sql = "SELECT * FROM employees WHERE employee_id = '$employee_id'";
$result = $conn->query($sql);
if ($result->num_rows == 1) {
    $employee = $result->fetch_assoc();
} else {
    echo "Employee not found";
    exit;
}

// Fetch assigned deliveries for the logged-in employee
$employee_id = $_SESSION['employee_id'];
$sql2 = "SELECT * FROM delivery WHERE employee_id = '$employee_id'";
$result2 = $conn->query($sql2);

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

// Fetch assigned repairs for the logged-in employee
$employee_id = $_SESSION['employee_id'];
// Fetch repair tasks assigned to the employee with user details
$sql_repairs = "SELECT repair.*, repair_service.repaired,users.username, users.phone, users.address 
                FROM repair_service 
                INNER JOIN repair ON repair_service.repair_id = repair.repair_id 
                INNER JOIN users ON repair.user_id = users.user_id 
                WHERE repair_service.employee_id = '$employee_id'";
$result_repairs = $conn->query($sql_repairs);
// Check for errors in the query
if ($result_repairs === false) {
    echo "Error fetching repair tasks: " . $conn->error;
    exit;}
// Process form submission to update repair task status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['repair_id']) && isset($_POST['status'])) {
    $repair_id = $_POST['repair_id'];
    $status = $_POST['status'];

    // Update repair  status in the database
    $sql_update = "UPDATE repair_service SET repaired = '$status' WHERE repair_id = '$repair_id'";
    if ($conn->query($sql_update) === TRUE) {
        echo "Repair task status updated successfully.";
        header("Location: empdashboard.php");
        exit;
    } else {
        echo "Error updating repair task status: " . $conn->error;
    }
}

// Fetch assigned pickups for the logged-in employee
$employee_id = $_SESSION['employee_id'];
$sql = "SELECT pickup.*, sell_item.name AS item_name, sell_item.address AS item_address, sell_item.phone AS item_phone, sell_item.furniture_image, sell_item.furniture_type, sell_item.quoted_price 
        FROM pickup 
        INNER JOIN sell_item ON pickup.sell_id = sell_item.sell_id 
        WHERE employee_id = '$employee_id'";
$result = $conn->query($sql);

// Check if pickup status is being updated
if (isset($_POST['pickup_id']) && isset($_POST['status'])) {
    $pickup_id = $_POST['pickup_id'];
    $status = $_POST['status'];

    // Update pickup status in the database
    $sql = "UPDATE pickup SET itempicked = '$status' WHERE pickup_id = '$pickup_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Pickup status updated successfully.";
    } else {
        echo "Error updating pickup status: " . $conn->error;
    }
}

// Display different sections based on employee's job role
$job_role = $employee['employee_job'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h1, h2 {
            color: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        form {
            display: inline;
        }

        select {
            padding: 10px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        a {
            display: inline-block;
            background-color: #009688;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        a:hover {
            background-color: #00796B;
        }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['employee_name']; ?>!</h1>

    <?php if ($job_role == 'delivery'): ?>
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
            if ($result2->num_rows > 0) {
                while ($row = $result2->fetch_assoc()) {
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
                    echo "<option value='delivered'>Delivered</option>";
                    echo "<option value='not delivered'>Not Delivered</option>";
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

    <?php elseif ($job_role == 'repair'): ?>
        <h2>Repair Tasks Assigned to You</h2>
    <?php if ($result_repairs->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Repair ID</th>
                <th>Service Name</th>
                <th>Description</th>
                <th>Username</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_repairs->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['repair_id']; ?></td>
                <td><?php echo $row['service_name']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo ($row['repaired'] == 1) ? 'repaired' : 'Not repaired'; ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="repair_id" value="<?php echo $row['repair_id']; ?>">
                        <select name="status">
                            <option value="repaired">repaired</option>
                            <option value="not repaired">Not repaired</option>
                        </select>
                        <button type="submit">Update Status</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No repair tasks assigned to you.</p>
    <?php endif; ?>

    <br>

    <?php elseif ($job_role == 'pickup'): ?>
        <h2>Assigned Pickups</h2>
    <table>
        <thead>
            <tr>
                <th>Pickup ID</th>
                <th>Sell ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Furniture Image</th>
                <th>Furniture Type</th>
                <th>Quoted Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['pickup_id'] . "</td>";
                    echo "<td>" . $row['sell_id'] . "</td>";
                    echo "<td>" . $row['item_name'] . "</td>";
                    echo "<td>" . $row['item_phone'] . "</td>";
                    echo "<td>" . $row['item_address'] . "</td>";
                    echo "<td><img src='../user/" . $row['furniture_image'] . "' alt='Furniture Image' style='width: 100px;'></td>";
                    echo "<td>" . $row['furniture_type'] . "</td>";
                    echo "<td>" . $row['quoted_price'] . "</td>";
                    echo "<td>" . ($row['itempicked'] == 'picked' ? 'Picked' : 'Not Picked') . "</td>";
                    // Add form to update pickup status
                    echo "<td>";
                    echo "<form action='' method='post'>";
                    echo "<input type='hidden' name='pickup_id' value='" . $row['pickup_id'] . "'>";
                    echo "<select name='status'>";
                    echo "<option value='picked'>Picked</option>";
                    echo "<option value='notpicked'>Not Picked</option>";
                    echo "</select>";
                    echo "<button type='submit'>Update Status</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No pickups assigned.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <br>
    <?php else: ?>
        <p>Invalid or unspecified job role</p>
    <?php endif; ?>

    <br>
    <a href="logout.php">Logout</a>
</body>
</html>