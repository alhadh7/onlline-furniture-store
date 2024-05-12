

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .btn{
            border:none;
    background:red;
    text-decoration:none;
    padding:12px;
    border-radius:20px; 
        }
        .btn-g{
            border:none;
    background:green;
    text-decoration:none;
    padding:12px;
    border-radius:20px;
        }
        body {
            font-family: kanit;
            display: flex;
            flex-direction: row;
        }

        .sidebar {
            width: 200px;
            background-color: #f1f1f1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: 650px;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        .sidebar a {
            padding: 8px;
            text-decoration: none;
            font-size: 18px;
            color: #000;
            display: block;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .sidebar a:hover {
            background-color: #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div class="sidebar">
        <a id="userManagement"><i class="fa-solid fa-user"></i> User Management</a>
        <a id="bandManagement"><i class="fa-solid fa-music"></i> Band Management</a>
        <a id="bookingReports"><i class="fa-solid fa-file"></i> Booking Reports</a>
    </div>

    <div class="content">
        <h1>Admin Dashboard</h1>

        <!-- User Management Section -->
        <div id="userManagementSection">
            <h2>User Management</h2>
            <table>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                <?php
                $usersSql = "SELECT id, username, email FROM users"; 
                $usersResult = $conn->query($usersSql);
                while ($user = $usersResult->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $user['id'] . '</td>';
                    echo '<td>' . $user['username'] . '</td>';
                    echo '<td>' . $user['email'] . '</td>';
                    // Add a delete button for each user
                    echo '<td>';
                    echo '<form method="post">';
                    echo '<input type="hidden" name="username" value="' . $user['username'] . '">';
                    echo '<input type="submit" class="btn" value="Delete User">';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>

        <!-- Band Management Section -->
        <div id="bandManagementSection" style="display: none;">
            <h2>Band Management</h2>
            <table>
                <tr>
                    <th>Band ID</th>
                    <th>Band Name</th>
                    <th>Action</th>
                </tr>
                <?php
                $bandsSql = "SELECT id, bandname FROM bands"; 
                $bandsResult = $conn->query($bandsSql);
                while ($band = $bandsResult->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $band['id'] . '</td>';
                    echo '<td>' . $band['bandname'] . '</td>';
                    // Add a delete button for each band
                    echo '<td>';
                    echo '<form method="post">';
                    echo '<input type="hidden" name="band_id" value="' . $band['id'] . '">';
                    echo '<input type="submit" class="btn" value="Delete Band">';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>

        <!-- Booking Reports Section -->
        <div id="bookingReportsSection" style="display: none;">
            <h2>Booking Reports</h2>
            <form method="get">
            <label for="fromDate">From Date:</label>
            <input type="date" id="fromDate" name="fromDate">
            <label for="toDate">To Date:</label>
            <input type="date" id="toDate" name="toDate">
            <input type="submit" class="btn-g" value="Generate Report">
        </form>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>User ID</th>
                <th>Band Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
                <th>Event Type</th>
                <th>Status</th>
            </tr>
            <?php
            // Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['fromDate']) && isset($_GET['toDate'])) {
                $fromDate = $_GET['fromDate'];
                $toDate = $_GET['toDate'];

                // Modify the SQL query to filter by date range
                $sql = "SELECT * FROM booking_requests WHERE date BETWEEN '$fromDate' AND '$toDate'";
                $result = $conn->query($sql);
                $bookingReports = $result->fetch_all(MYSQLI_ASSOC);

                foreach ($bookingReports as $report) {
                    $totalAmount = count($bookingReports) * 100; // Calculate total amount gained
                    echo '<tr>';
                    echo '<td>' . $report['id'] . '</td>';
                    echo '<td>' . $report['user_id'] . '</td>';
                    echo '<td>' . $report['bandname'] . '</td>';
                    echo '<td>' . $report['date'] . '</td>';
                    echo '<td>' . $report['time'] . '</td>';
                    echo '<td>' . $report['location'] . '</td>'; // Display location
                    echo '<td>' . $report['event_type'] . '</td>'; // Display event_type
                    echo '<td>' . $report['status'] . '</td>'; 
                    echo '</tr>';
                } // Display the total amount after the loop
                echo 'Total Amount Gained: $' . $totalAmount ;
            }
            ?>
        </table>
        </div>
    </div>

    <script>
    // JavaScript to handle switching between sections
    console.log("Script loaded."); // Add this line to check if the script is loaded

    document.getElementById("userManagement").addEventListener("click", function() {
        showSection("userManagementSection");
    });

    document.getElementById("bandManagement").addEventListener("click", function() {
        showSection("bandManagementSection");
    });

    document.getElementById("bookingReports").addEventListener("click", function() {
        showSection("bookingReportsSection");
    });

    function showSection(sectionId) {
    console.log("Switching to section:", sectionId); // Add this line

    var sections = ["userManagementSection", "bandManagementSection", "bookingReportsSection"];
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