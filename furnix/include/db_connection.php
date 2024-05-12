$conn = new mysqli("localhost", "alhad", "1234", "main");
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }