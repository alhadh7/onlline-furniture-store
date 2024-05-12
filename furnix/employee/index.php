<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
// Database connection
$conn = new mysqli("localhost", "alhad", "1234", "main");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

        // Perform SQL query to validate username and password
        $sql = "SELECT * FROM employees WHERE employee_name = '$username' AND employee_password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // Employee authenticated
            $row = $result->fetch_assoc();
            $_SESSION['employee_id'] = $row['employee_id'];
            $_SESSION['employee_name'] = $row['employee_name'];
            // Redirect to employee dashboard
            header("Location: empdashboard.php");
            exit();
        } else {
            // Invalid credentials
            echo "Invalid username or password. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>
    <!-- Include any CSS stylesheets here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            width: 320px;
            max-width: 90%;
            margin: 0 auto;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 12px);
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            outline: none;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>   
</head>
<body>
    <div class="container">
        <h1>Employee Login</h1>

        <form action="index.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Login">
        </form>

        <div class="error">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($error_message)) {
                echo $error_message;
            }
            ?>
        </div>
    </div>
</body>
</html>
