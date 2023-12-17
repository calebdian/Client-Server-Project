<?php
// Connect to the database (replace with your actual database credentials)
session_start();
$conn = new mysqli("localhost", "root", "", "users");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get admin input from the form
$admin_username = $_POST['username'];
$admin_password = $_POST['password'];

// You should hash the password  for security, but for simplicity, we'll compare it as-is
// In a real application, use password_hash() when storing passwords

// Query to check if the entered admin credentials are valid
$sql = "SELECT * FROM admin WHERE Name = '$admin_username' AND Password = '$admin_password'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    // Admin authentication successful
    echo "Admin login successful!";
    // Redirect to the admin dashboard or home page
    $_SESSION['admin_name'] = $admin_username;
    header("Location: AdminInterface.php");
    echo $_SESSION['admin_name'];
} else {
    // Admin authentication failed, redirect to the login page with an error message
    echo "Admin login failed. Incorrect username or password. ";
    echo "<br/>";
    echo "<br/>";
    echo '<a href="index.php" style="background:green; text-decoration: none;padding:10px; color:white; border-radius: 10px; color:white;">Back</a>';
    // You can also provide a link to the login page here
}

// Close the database connection
$conn->close();
?>
