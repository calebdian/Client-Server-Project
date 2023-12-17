<?php
// Connect to the database (replace with your actual database credentials)
$conn = new mysqli("localhost", "root", "", "Users");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input from the form
$username = $_POST['Username'];
$email = $_POST['email'];
$password = $_POST['password'];
$ConfirmPassword = $_POST['confirmPassword'];
$Tel = $_POST['TelNo'];

// You should hash the password for security, but for simplicity, we'll insert it as-is
// In a real application, use password_hash() to securely store passwords

// Insert user details into the database
$checkQuery = "SELECT * FROM user WHERE Email = '$email' OR Name = '$username'";
$result = $conn->query($checkQuery);

if ($result->num_rows > 0) {
    // A user with the same email or username already exists
    echo "Registration failed: A user with the same email or username already exists.";
} else {
    // Insert user details into the database
    $sql = "INSERT INTO user (Name, Email, Password, `Confirm Password`, `Tel No`) VALUES ('$username', '$email', '$password', '$ConfirmPassword', '$Tel')";

if ($conn->query($sql) === TRUE) {
    echo "Registration successful!";
    header("Location: index.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}

// Close the database connection
$conn->close();
?>
