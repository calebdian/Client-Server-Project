<?php

session_start();
// Connect to the database (replace with your actual database credentials)
$conn = new mysqli("localhost", "root", "", "Users");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input from the form
$login_identifier = $_POST['email']; // This can be either an email or a username
$password = $_POST['password'];

// Check if the input can be interpreted as an email
if (filter_var($login_identifier, FILTER_VALIDATE_EMAIL)) {
    $sql = "SELECT * FROM user WHERE Email = '$login_identifier' AND Password = '$password'";
    
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Set the user_name session variable to the name from the database
        $_SESSION['user_name'] = $row['Name'];
        header("Location: UserInterface.php");
    } else {
        // Authentication failed, redirect to the login page with an error message
        echo "Login failed. Incorrect email or password.";
       
    }
} else {
    // If it's not an email, assume it's a username
    $sql = "SELECT * FROM user WHERE Name = '$login_identifier' AND Password = '$password'";

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Set the user_name session variable to the username
        $_SESSION['user_name'] = $login_identifier;
        header("Location: UserInterface.php");
    } else {
        // Authentication failed, redirect to the login page with an error message
        echo "Login failed. Incorrect username or password.";
        echo "<br/>";
        echo "<br/>";
        echo '<a href="index.php" style="background:green; text-decoration: none;padding:10px; color:white; border-radius: 10px; color:white;">Back</a>';
    }
}

// Close the database connection
$conn->close();
?>
