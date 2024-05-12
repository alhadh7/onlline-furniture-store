<?php
session_start();

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// Fetch repair requests from the database
$conn = new mysqli("localhost", "alhad", "1234", "main");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT repair.*, users.username, users.phone, users.address, repair_service.repaired 
        FROM repair
        INNER JOIN users ON repair.user_id = users.user_id
        INNER JOIN repair_service ON repair.repair_id = repair_service.repair_id
        WHERE repair.user_id = '$user_id'";
$result = $conn->query($sql);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    
    // Insert the service request into the database
    $sql = "INSERT INTO repair (service_name, description, user_id) 
            VALUES ('$service_name', '$description', '$user_id')";

    if ($conn->query($sql) === TRUE) {
        echo "Service request submitted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$sql_pickup = "SELECT pickup.*, sell_item.name, sell_item.address, sell_item.phone, sell_item.furniture_image, sell_item.furniture_type, sell_item.quoted_price 
               FROM pickup
               INNER JOIN sell_item ON pickup.sell_id = sell_item.sell_id
               WHERE sell_item.user_id = '$user_id'";
$result_pickup = $conn->query($sql_pickup);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include('..\include\head.php');?>
<style>

label {
    display: block;
    margin-bottom: 5px;
}

input[type="text"],
textarea {
    width: 30%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

select {
    width: 10%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

/* Apply styles to the table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 250px;
}

table th,
table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

table th {
    background-color: #f2f2f2;
}

</style>
</head>
<body>
<?php include('..\include\header.php');?>

<div class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row">
            <h1>Repair Service</h1>
            <form  method="POST">
                <label for="service_name">Service Name:</label><br>
                <select id="service_name" name="service_name" required>
                    <option value="repair">repair</option>
                    <option value="polish">polish</option>
                    <!-- Add options dynamically from the database if needed -->
                </select><br><br>
                <label for="furniture_type">Furniture Type:</label><br>
                <input type="text" name="furniture_type"><br>
                <label for="description">Description:</label><br>
                <textarea id="description" name="description" rows="4" cols="50" required></textarea><br><br>
                <input type="submit" value="Request Service">
            </form>
        </div>
    </div>
</div>


    <div class="container">
        <div class="row">
            <h1>Current Repairs</h1>
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Repair ID</th>
                            <th>Service Name</th>
                            <th>Description</th>
                            <th>Repair Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['repair_id']; ?></td>
                                <td><?php echo $row['service_name']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['repaired']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No repair requests currently.</p>
            <?php endif; ?>
        </div>
    

    <h1>Sell</h1>
    <form action="submit_order.php" method="POST" enctype="multipart/form-data">
        <label for="furniture_type">Furniture Type:</label><br>

        <input type="text" id="furniture_type" name="furniture_type" required><br><br>

    <label for="furniture_image">Furniture Image:</label><br>
        <input type="file" id="furniture_image" name="furniture_image" accept="image/*" required><br><br>
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="address">Address:</label><br>
        <textarea id="address" name="address" rows="4" required></textarea><br><br>

        <label for="phone">Phone Number:</label><br>
        <input type="text" id="phone" name="phone" required><br><br>
        <label for="quoted_price">Quoted Price:</label><br>
        <input type="number" id="quoted_price" name="quoted_price" min="0" required><br><br>

        <input type="submit" value="Submit Order">
    </form>

<br>
<h1>View Pickups</h1>
            <?php if ($result_pickup->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Furniture Image</th>
                            <th>Furniture Type</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                       
                            <th>is item picked ?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_pickup->fetch_assoc()): ?>
                            <tr>
                                <td><img src="<?php echo $row['furniture_image']; ?>" alt="Furniture Image" style="width: 100px;"></td>
                                <td><?php echo $row['furniture_type']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['address']; ?></td>
                                <td><?php echo $row['phone']; ?></td>
                     
                                <td><?php echo $row['itempicked']; ?></td> <!-- Display Item Picked status -->
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No pickups available.</p>
            <?php endif; ?>

</div>
<?php include('..\include\footer.php');?>
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/tiny-slider.js"></script>
<script src="../js/custom.js"></script>
</body>
</html>