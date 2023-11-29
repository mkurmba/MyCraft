<?php
    $conn = new mysqli("localhost", "root", "", "account_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $loginName = $_POST["loginname"]; // Accept either email or loginname
        $password = $_POST["password"];

        //allow either email / user name
        $sql = "SELECT * FROM users WHERE email = '$loginName' OR loginname = '$loginName'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $row["password"])) {
                echo "Login successful!";
            } else {
                echo "Invalid password";
            }
        } else {
            echo "User not found";
        }
    }

    $conn->close();
?>