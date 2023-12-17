<?php
session_start();

// Connect to the database (replace with your actual database credentials)
$conn = new mysqli("localhost", "root", "", "users");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_name = $_SESSION['user_name'];

// Fetch received messages for the user
$sql_received = "SELECT Sender, Comment FROM messages WHERE Receiver = '$user_name'";
$result_received = $conn->query($sql_received);

// Fetch sent messages by the user
$sql_sent = "SELECT Receiver, Comment FROM messages WHERE Sender = '$user_name'";
$result_sent = $conn->query($sql_sent);

$conn->close();
?>
<html>
 <head>
  <title>Messaging area</title> 
 </head>
  <body>
     <h1>User Comments section</h1>
     <form action="" method="post">
     <input type="text" name="admin_name" value="<?php echo $_SESSION['user_name']; ?>" readonly><br>
      <label for="userSelect">Choose a user:</label>
        <select id="userSelect" name="user">
            <?php
            // Connect to the database (replace with your actual database credentials)
            $conn = new mysqli("localhost", "root", "", "users");

            // Check the connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Query to retrieve user names from the database
            $sql = "SELECT Name FROM user";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $userName = $row['Name'];
                    echo "<option value='$userName'>$userName</option>";
                }
            } else {
                echo "<option value=''>No users found</option>";
            }
            echo "<option value='All'>All</option>";
            // Close the database connection
            $conn->close();
            ?>
        </select><br>
     <textarea name="Message" id="Message" placeholder="Comment on something" cols="20" rows="5"></textarea><br>
     <input type="submit" value="send">
     </form>

     <h2>Received Messages:</h2>
     <?php
     if ($result_received->num_rows > 0) {
         while ($row_received = $result_received->fetch_assoc()) {
             $sender_received = $row_received['Sender'];
             $message_received = $row_received['Comment'];

             echo "<p>From: $sender_received<br>$message_received</p>";
         }
     } else {
         echo "No received messages.";
     }
     ?>

     <h2>Sent Messages:</h2>
     <?php
     if ($result_sent->num_rows > 0) {
         while ($row_sent = $result_sent->fetch_assoc()) {
             $receiver_sent = $row_sent['Receiver'];
             $message_sent = $row_sent['Comment'];

             echo "<p>To: $receiver_sent<br>$message_sent</p>";
         }
     } else {
         echo "No sent messages.";
     }
     ?>
  </body>
</html>
