<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2, h3 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: left;
        }
        table img {
        max-width: 100px; /* Adjust the value as needed */
        height: auto; /* Maintain aspect ratio */
        }
        form {
            margin-bottom: 20px;
        }
        form label {
            display: block;
            margin-bottom: 5px;
        }
        form input[type="text"],
        form input[type="email"],
        form input[type="password"]
                         {
            width: 30%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form input[type="submit"],
        button {
            padding: 8px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        form input[type="submit"]:hover,
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php
// Database connection
$conn = new mysqli("localhost", "alhad", "1234", "main");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create Employees table if it doesn't exist
$sql_create_table = "CREATE TABLE IF NOT EXISTS employees (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_name VARCHAR(100) NOT NULL,
    employee_password VARCHAR(100) NOT NULL,
    employee_email VARCHAR(100) NOT NULL
)";
if ($conn->query($sql_create_table) === TRUE) {
    echo "";
} else {
    echo "Error creating table: " . $conn->error;
}

// Add Employee
if(isset($_POST['add_employee'])) {
    $empname = $_POST['empname'];
    $emppassword = $_POST['emppassword'];
    $empemail = $_POST['empemail'];
    $empjob = $_POST['empjob'];
    $sql = "INSERT INTO employees (employee_name, employee_password, employee_email ,employee_job) VALUES ('$empname', '$emppassword', '$empemail' ,'$empjob')";
    if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete Employee
if(isset($_POST['delete_employee'])) {
    $employee_id = $_POST['employee_id'];

    $sql = "DELETE FROM employees WHERE employee_id='$employee_id'";
    if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
// Check if form is submitted
if(isset($_POST['assign_delivery'])) {
    $Order_id = $_POST['order_id'];
    $Employee_id = $_POST['employee_id'];

    // Insert delivery record into the delivery table
    $sql = "INSERT INTO delivery (order_id, employee_id, delivery_timestamp, delivered) VALUES ('$Order_id', '$Employee_id', CURRENT_TIMESTAMP(), '0')";
    if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        echo "Error assigning delivery: " . $conn->error;
    }
}

function viewEmployees($conn)
{
    $sql = "SELECT * FROM employees";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Employees</h2>";
        echo "<table>";
        echo "<tr><th>Employee ID</th><th>Name</th><th>Email</th><th>job</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['employee_id'] . "</td>";
            echo "<td>" . $row['employee_name'] . "</td>";
            echo "<td>" . $row['employee_email'] . "</td>";
            echo "<td>" . $row['employee_job'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No employees found.";
    }
}

// Function to fetch and display deliveries
function viewDeliveries($conn)
{
    $sql = "SELECT * FROM delivery";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Deliveries</h2>";
        echo "<table>";
        echo "<tr><th>Delivery ID</th><th>Order ID</th><th>Employee ID</th><th>Delivery Timestamp</th><th>Delivered</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['delivery_id'] . "</td>";
            echo "<td>" . $row['order_id'] . "</td>";
            echo "<td>" . $row['employee_id'] . "</td>";
            echo "<td>" . $row['delivery_timestamp'] . "</td>";
            echo "<td>" . ($row['delivered'] ? 'Yes' : 'No') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No deliveries found.";
    }
}
function viewRepairservices($conn)
{
    $sql = "SELECT * FROM repair_service";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<h2>Repair Services</h2>";
        echo "<table>";
        echo "<tr><th>Repair ID</th><th>Employee ID</th><th>Delivered</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['repair_id'] . "</td>";
            echo "<td>" . $row['employee_id'] . "</td>";
            echo "<td>" . ($row['repaired'] ? 'Yes' : 'No') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No repair services found.";
    }
    
}

// HANDLE pickup assignment Check if the form is submitted
if(isset($_POST['assign_pickup'])) {
    // Retrieve form data
    $pickups_id = $_POST['pickup_id'];
    $employees_id = $_POST['employee_id'];


    // Update the pickup request with the assigned employee
    $sql = "INSERT INTO pickup (sell_id, employee_id) VALUES ('$pickups_id', '$employees_id')";
    if ($conn->query($sql) === TRUE) {
        echo "Pickup request assigned successfully.";
    } else {
        echo "Error assigning pickup request: " . $conn->error;
    }

    // Close database connection
    $conn->close();
}

// Handle repair assignment if form is submitted
if (isset($_POST['assignRepair'])) {
    $repairId = $_POST['repair_id'];
    $employeeId = $_POST['employee_id'];
    
    // Perform repair assignment query here
    $sql = "INSERT INTO repair_service (repair_id, employee_id, repaired) 
    VALUES ('$repairId', '$employeeId', 0)";
    if ($conn->query($sql) === TRUE) {
        // Assignment successful
        echo "<script>alert('Repair request assigned successfully'); window.location.href = 'dashboard.php';</script>";
    } else {
        // Error occurred during assignment
        echo "<script>alert('Error assigning repair request');</script>";
    }
}


function displayPickupRequests($conn) {

    // Fetch pickup requests
    $sql = "SELECT * FROM sell_item";
    $result = $conn->query($sql);

    // Display pickup requests in a table
    echo "<h1>Pickup Requests</h1>";
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>sell item ID</th>";
    echo "<th>Name</th>";
    echo "<th>Address</th>";
    echo "<th>Phone</th>";
    echo "<th>Furniture Type</th>";
    echo "<th>Quoted Price</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['sell_id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['address'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>" . $row['furniture_type'] . "</td>";
            echo "<td>" . $row['quoted_price'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No pickup requests available</td></tr>";
    }
    echo "</tbody>";
    echo "</table>";


}
?>
    <div class="container">
        <h1>Admin Dashboard</h1>

        <!-- Employee Management Section -->
        <h2>Employee Management</h2>
        <form action="" method="post">
            <label for="empname">Employee Name:</label>
            <input type="text" id="empname" name="empname" required><br><br>
            
            <label for="emppassword">Password:</label>
            <input type="password" id="emppassword" name="emppassword" required><br><br>
            
            <label for="empemail">Email:</label>
            <input type="email" id="empemail" name="empemail" required><br><br>
            
            <label for="empjob">job:</label>
            <input type="text" id="empjob" name="empjob" required><br><br>

            <input type="submit" name="add_employee" value="Add Employee">
        </form>

        <h3>Delete Employee</h3>
        <form action="" method="post">
            <label for="employee_id">Employee ID:</label>
            <input type="text" id="employee_id" name="employee_id" required><br><br>
            
            <input type="submit" name="delete_employee" value="Delete Employee">
        </form>

        <!-- Assign Delivery Section -->
        <h1>Assign Delivery</h1>
        <form action="" method="post">
            <label for="order_id">Order ID:</label>
            <input type="text" id="order_id" name="order_id" required><br><br>
            
            <label for="emp_id">Employee ID:</label>
            <input type="text" id="employee_id" name="employee_id" required><br><br>
            
            <input type="submit" name="assign_delivery" value="Assign Delivery">
        </form>
        <!-- Assign Pickup Section -->
        <h1>Assign Pickup Request to Employee</h1>
        <form action="" method="POST">
        <label for="pickup_id">Pickup ID:</label>
        <input type="text" id="pickup_id" name="pickup_id" required><br><br>
        
        <label for="employee_id">Employee ID:</label>
        <input type="text" id="employee_id" name="employee_id" required><br><br>
        
        <input type="submit" name="assign_pickup" value="Assign Pickup">
    </form>
    <!-- Assign Repair Requests Section -->
    <h2>Assign Repair Requests</h2>
    <form method="post">
        <label for="repair_id">Request ID:</label>
        <input type="text" id="repair_id" name="repair_id" required><br><br>
        
        <label for="employee_id">Employee ID:</label>
        <input type="text" id="employee_id" name="employee_id" required><br><br>
        
        <input type="submit" name="assignRepair" value="Assign Repair">
    </form>

        <!-- View Employees -->
        <?php viewEmployees($conn); ?>

        <!-- View Deliveries -->
        <?php viewDeliveries($conn); ?>

        <!-- View Repair Requests Section -->
    <h2>View Repair Requests</h2>
    <table>
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Service Name</th>
                <th>Description</th>
                <th>User ID</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch repair requests from the database and display them in rows
            $sql = "SELECT * FROM repair";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['repair_id'] . "</td>";
                    echo "<td>" . $row['service_name'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td>" . $row['user_id'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No repair requests found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php viewRepairservices($conn); ?>
        <!-- View Users Section -->
        <h2>User Management</h2>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th> <!-- New column for Delete action -->
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch users from the database and display them in rows
                $sql = "SELECT * FROM users";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['phone'] . "</td>";
                        echo "<td><form method='post'><input type='hidden' name='deleteUserId' value='" . $row['user_id'] . "'><button type='submit' name='deleteUser'>Delete</button></form></td>"; // Delete button
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    
    <?php displayPickupRequests($conn); ?>
 <!-- View All Orders Section -->
 <h2>View All Orders</h2>
        <?php viewOrders($conn); ?>

        <!-- Add Product Section -->
        <h2>Add Product</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="productName">Product Name:</label>
            <input type="text" id="productName" name="productName" required><br><br>
            
            <label for="productDescription">Description:</label>
            <textarea id="productDescription" name="productDescription" required></textarea><br><br>
            
            <label for="productPrice">Price:</label>
            <input type="text" id="productPrice" name="productPrice" required><br><br>
            
            <label for="productImage">Image:</label>
            <input type="file" id="productImage" name="productImage" required><br><br>
            
            <input type="submit" name="add_product" value="Add Product">
        </form>
        <h2>Remove Product</h2>

        <?php
            // Fetch products from the database
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

        // Check if there are any products
        if ($result !== false && $result->num_rows > 0) {
         // Display products in a table
        while ($row = $result->fetch_assoc()) {
        // Display product details in table rows
        echo "<tr>"; 
        echo "<td>" . $row['product_id'] . "</td>";
        echo "<td><img src='../images/" . $row['product_image'] . "' alt='Product Image' style='max-width: 100px; height: auto;'></td>";
        echo "<td>" . $row['product_name'] . "</td>";
        echo "<td>" . $row['product_description'] . "</td>";
        echo "<td>" . $row['product_price'] . "</td>";
        echo "<td>";
        echo "<br>";
        echo "<form action='' method='post'>";
        echo "<input type='hidden' name='product_id' value='" . $row['product_id'] . "'>";
        echo "<input type='submit' name='delete_product' value='Delete'>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
        }
        } else {
        // Display a message if no products are available
         echo "<tr><td colspan='6'>No products available.</td></tr>";
        }
        ?>
        <h1>Admin Dashboard - Report</h1>
        <form action="" method="post">
            <label for="fromDate">From Date:</label>
            <input type="date" id="fromDate" name="fromDate" required><br><br>
            <label for="toDate">To Date:</label>
            <input type="date" id="toDate" name="toDate" required><br><br>
            <input type="submit" name="generateReport" value="Generate Report">
        </form>
        <?php
        if (isset($_POST['generateReport'])) {
            $fromDate = $_POST['fromDate'];
            $toDate = $_POST['toDate'];

            // Fetch orders and calculate total amount within the specified date range
            $sql = "SELECT SUM(total_price) AS total_amount FROM orders WHERE order_date BETWEEN '$fromDate' AND '$toDate'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $totalAmount = $row['total_amount'];

            // Fetch orders within the specified date range
            $sql = "SELECT order_id, order_date, total_price FROM orders WHERE order_date BETWEEN '$fromDate' AND '$toDate'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<h2>Orders from $fromDate to $toDate</h2>";
                echo "<table>";
                echo "<tr><th>Order ID</th><th>Order Date</th><th>Total Price</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['order_id'] . "</td>";
                    echo "<td>" . $row['order_date'] . "</td>";
                    echo "<td>$" . $row['total_price'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "<p>Total Amount: $" . $totalAmount . "</p>";
            } else {
                echo "No orders found within the specified date range.";
            }
        }
        ?>
    </div>
    </div>
    <?php
    // Handle form submission to add a product
    if(isset($_POST['add_product'])) {
        $productName = $_POST['productName'];
        $productDescription = $_POST['productDescription'];
        $productPrice = $_POST['productPrice'];
        $productImage = $_FILES['productImage']['name'];

        // Upload image to server
        $targetDir = "../images/";
        $targetFile = $targetDir . basename($_FILES["productImage"]["name"]);
        move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile);

        // Insert product into database
        $sql = "INSERT INTO products (product_name, product_description, product_price, product_image) VALUES ('$productName', '$productDescription', '$productPrice', '$productImage')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Product added successfully');</script>";
        } else {
            echo "<script>alert('Error adding product: " . $conn->error . "');</script>";
        }
    }
    // handle remove product
    if(isset($_POST['delete_product'])) {
        $productId = $_POST['product_id'];
    
        // SQL to delete product from the database
        $sql = "DELETE FROM products WHERE product_id='$productId'";
        
        if ($conn->query($sql) === TRUE) {
            echo "Product deleted successfully.";
        } else {
            echo "Error deleting product: " . $conn->error;
        }
    }
    // Function to view all orders
    function viewOrders($conn) {
        $sql = "SELECT * FROM orders";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Order ID</th><th>User ID</th><th>Date</th><th>Total Price</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['order_id'] . "</td>";
                echo "<td>" . $row['user_id'] . "</td>";
                echo "<td>" . $row['order_date'] . "</td>";
                echo "<td>" . $row['total_price'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No orders found.";
        }
    }
    ?>
    <?php
    // Handle user deletion if deleteUserId parameter is posted
    if (isset($_POST['deleteUserId'])) {
        $deleteUserId = $_POST['deleteUserId'];
        // Perform deletion query here
        $deleteSql = "DELETE FROM users WHERE user_id = $deleteUserId";
        if ($conn->query($deleteSql) === TRUE) {
            // Deletion successful
            echo "<script>alert('User deleted successfully'); window.location.href = 'admin_dashboard.php';</script>";
        } else {
            // Error occurred during deletion
            echo "<script>alert('Error deleting user');</script>";
        }
    }
    
           
?>
    
    </div>
    <script>
    // JavaScript to handle switching between sections
    console.log("Script loaded."); // Add this line to check if the script is loaded

    document.getElementById("usermanagement").addEventListener("click", function() {
        showSection("usermanagementSection");
    });

    document.getElementById("productmanagement").addEventListener("click", function() {
        showSection("productmanagementSection");
    });
    document.getElementById("assigndelivery").addEventListener("click", function() {
        showSection("assigndeliverySection");
    });    
    document.getElementById("assignpickup").addEventListener("click", function() {
        showSection("assignpickupSection");
    });
    document.getElementById("assignrepair").addEventListener("click", function() {
        showSection("assignrepairSection");
    });
    document.getElementById("report").addEventListener("click", function() {
        showSection("reportSection");
    });

    function showSection(sectionId) {
    console.log("Switching to section:", sectionId); // Add this line

    var sections = ["usermanagementSection", "productmanagementSection", "reportSection","assignrepairSection","assignpickupSection","assigndeliverySection"];
    for (var i = 0; i < sections.length; i++) {
        var section = document.getElementById(sections[i]);
        if (sections[i] === sectionId) {
            section.style.display = "block";
        } else {
            section.style.display = "none";
        }
    }
}
</script>
</body>
</html>
