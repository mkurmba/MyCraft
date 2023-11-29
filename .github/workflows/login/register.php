<?php
    $conn = new mysqli("localhost", "root", "", "account_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstName = $_POST["first-name"];
        $lastName = $_POST["last-name"];
        $email = $_POST["email"];
        $loginname = $_POST["loginname"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirm-password"];

        // Check if the email is already registered
        $emailCheck = "SELECT * FROM users WHERE email = '$email'";
        $emailResult = $conn->query($emailCheck);

        if ($emailResult->num_rows > 0) {
            echo "This email is already registered.";
            exit;
        }

        // Check if the loginname is already taken
        $loginnameCheck = "SELECT * FROM users WHERE loginname = '$loginname'";
        $loginnameResult = $conn->query($loginnameCheck);

        if ($loginnameResult->num_rows > 0) {
            echo "This loginname is already taken. Please choose a different one.";
            exit;
        }

        // Check if password and confirm password match
        if ($password !== $confirmPassword) {
            echo "Password and confirm password do not match.";
            exit;
        }

        // Check password complexity
        if (strlen($password) < 8 || !preg_match("#[0-9]+#", $password) || !preg_match("#[A-Z]+#", $password)) {
            echo "Password must be at least 8 characters long and include at least one uppercase letter and one number.";
            exit;
        }

        // Hash
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (first_name, last_name, email, loginname, password) VALUES ('$firstName', '$lastName', '$email', '$loginname', '$hashedPassword')";

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
?>