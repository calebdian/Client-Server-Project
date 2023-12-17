<?php
session_start();
ob_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle the form submission and database insert here
    $user_name = $_SESSION['user_name'];
    $selected_user = $_POST['user'];
    $message = $_POST['Message'];
    
    // Connect to the database (replace with your actual database credentials)
    if (!empty($message)) {
    $conn = new mysqli("localhost", "root", "", "users");

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql_check_duplicate = "SELECT * FROM messages 
                                WHERE Sender = '$user_name' 
                                AND Receiver = '$selected_user' 
                                AND Comment = '$message'";

        $result_check_duplicate = $conn->query($sql_check_duplicate);

    if ($result_check_duplicate->num_rows == 0) {
        if ($user_name != $selected_user){
        // Insert message into the database
        $sql = "INSERT INTO messages (Sender, Receiver, Comment) VALUES ('$user_name', '$selected_user', '$message')";
        

        if ($conn->query($sql) === TRUE) {
            $_SESSION['submission_status'] = 'success';

        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            $_SESSION['submission_status'] = 'error';
        }
    } else {
        $_SESSION['submission_status'] = 'error';
    }
} else {
    $_SESSION['submission_status'] = 'duplicate';
}

    // Close the database connection
    $conn->close();
 }
}
unset($_SESSION['submission_status']);
// Fetch and display sent messages to a specific user

// Connect to the database (replace with your actual database credentials)
$conn = new mysqli("localhost", "root", "", "users");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch messages sent to the specific user
$messages = array();

$user_name = $_SESSION['user_name'];
// Close the database connection
$sql_received = "SELECT Sender, Comment FROM messages WHERE Receiver = '$user_name'";
$result_received = $conn->query($sql_received);

// Fetch sent messages by the user
$sql_sent = "SELECT Receiver, Comment FROM messages WHERE Sender = '$user_name'";
$result_sent = $conn->query($sql_sent);

// Organize messages by sender
while ($row_received = $result_received->fetch_assoc()) {
    $sender_received = $row_received['Sender'];
    $message_received = $row_received['Comment'];
    $messages[$sender_received]['received'][] = $message_received;
}

// Organize messages by receiver
while ($row_sent = $result_sent->fetch_assoc()) {
    $receiver_sent = $row_sent['Receiver'];
    $message_sent = $row_sent['Comment'];
    $messages[$receiver_sent]['sent'][] = $message_sent;
}

if (!isset($_SESSION['first_login'])) {
    $_SESSION['first_login'] = true;

    // Query to retrieve user names from the database
    $sql_users = "SELECT Name FROM user";
    $result_users = $conn->query($sql_users);

    if ($result_users->num_rows > 0) {
        $user_names = array();
        while ($row = $result_users->fetch_assoc()) {
            $user_names[] = $row['Name'];
        }

        // Randomly select a user
        $selected_user = $user_names[array_rand($user_names)];

        // Update the session variable with the selected user
        $_POST['user'] = $selected_user;
    }
}
$conn->close();
?>
<html>
 <head>
  <title>Messaging area</title> 
  <style>
     body{
    padding: 20px;
    padding-right:20px;
    }
 .logout-button {
            background-color: #dc3545; /* Red color for logout */
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            margin-top: 10px;
 }

        /* Hover effect for the logout button */
  .logout-button:hover {
            background-color: #c82333;
        }
 .mast{
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    width: 100%;
    margin: 0 auto;
    box-shadow: 2px 0px 0px rgba(0,0,0,0.5);
    background: linear-gradient(green,orange ); 
    padding: 0 20px;
    border-radius: 10px;
   
 }
 .messages {
     display: flex;
    flex-direction: column;
    
 }

 .received-messages {
     align-items: flex-start;
     flex-basis: 50%;
     height: 100%;
     border-right: 1px solid black;
      /* Align sent messages to the left */
 }

 .sent-messages {
     align-items: flex-start; /* Align received messages to the right */
     flex-basis: 50%;
     border-left: 1px solid black;
     padding: 0 20px;
 }

 .sent-message {
     background-color: #87CEEB; /* Style sent messages */
     margin: 10px;
     padding: 10px;
     border-radius: 10px;
     width: 120px;
     word-wrap: break-word; 
    clip-path: polygon(15% 0, 100% 0, 100% 100%, 0 100%);
 }

 .received-message {
     background-color: #90EE90; /* Style received messages */
     margin: 10px;
     padding: 5px;
     border-radius: 10px;
     width: 120px;
     word-wrap: break-word; 
    clip-path: polygon(0 0, 100% 0, 85% 100%, 0 100%);
 }
 /* Styles for the User Comments section */
.user-comments-form {
    margin: 0 auto;
    background-color: #f9f9f9;
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 5px;
    text-align: center;
    box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
    width: 100%;
    margin-bottom: 20px;
    background: linear-gradient(green,orange );
}
.userNameBox{
    border: none;
    outline:none;
    font-size: 20px;
    text-align: center;
    text-shadow: 2px 2px 3px rgba(156, 226, 78,0.5);
    background: transparent;
}
.selector{
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    
}
.select-label { 
    font-weight: bold;
    margin-right: 10px;
    margin-bottom: 10px;
}

.user-select {
    padding: 10px;
    width: 50%;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 15px;
    margin-right: 10px;
}
.align{
    display: flex;
    flex-direction: column;
}
input[type="number"]{
  width: 50%;
  padding: 10px;
  margin-right: 0px;
  border: none;
  outline:none;
  border: 1px solid #ddd;
  border-radius: 5px;
}
.one{
    width: 80%;
    display: flex;
    padding: 5px;
    border: 1px solid #ddd;
    box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
    background: linear-gradient(green,orange);
    margin-bottom: 20px;
    margin-top: 50px;
}
.comment-textarea {
    flex-basis: 80%;
    padding: 10px;
    height: 40px;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 15px;
    resize: none;
    margin: 0 auto;
    color: #0056b3;
    font-weight: 900;
    vertical-align: middle;
}

.submit-button {
    background-color: #007BFF;
    color: #fff;
    border: none;
    flex-basis: 20%;
    border-radius: 5px;
    padding: 5px 20px;
    cursor: pointer;
    font-size: 22px;
    height: 40px;
}

/* Hover effect for the submit button */
.submit-button:hover {
    background-color: #0056b3;
}

</style>
 </head>
  <body>
  <h1 style="text-align:center">User Comments section</h1>
  <div class="user-comments-form">
<form action="" method="post" class="" id="commentForm">
    <input type="text" name="admin_name" class= "userNameBox" value="Welcome ,<?php echo $_SESSION['user_name']; ?>" readonly><br>
    <input type="submit" value="Logout" class="logout-button" name="logout"></br></br>
     <div class="selector"> 
    <label for="userSelect" class="select-label">Choose a user:</label>
    <select id="userSelect" name="user" class="user-select" onchange="document.getElementById('commentForm').submit();">
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
                $onlineStatus = isset($_SESSION[$userName]) ? '&#128994;' : ''; // Green dot emoji
                $selectedAttr = ($userName == $selected_user) ? 'selected' : '';
                echo "<option value='$userName' $selectedAttr>$userName $onlineStatus</option>";
            }
            echo "<option value='Admin'>Admin</option>";
        } else {
            echo "<option value=''>No users found</option>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </select><br>
    </div> 
    <label for="phone">Phone Number:</label>
    <input type="number" name="phoneNo" id="phoneNo" placeholder="Enter phone number">  
    </div>


    
     
<?php
if (isset($_POST['user']) && !empty($_POST['user'])) {
    $selected_user = $_POST['user'];
    echo '<h2  style="text-align:center;">chatting with ' . $selected_user . '</h2>';
    echo '<div class="mast">';
    echo '<div class="messages received-messages">';
   echo '<h2>Received Messages:</h2>';
foreach ($messages as $sender => $messageGroup) {
   if ($sender === $selected_user){
   if (isset($messageGroup['received']) && !empty($messageGroup['received'])) { 
       foreach ($messageGroup['received'] as $message_received) {
           echo "<p class='received-message'>$message_received</p>";
       }
   }
}
}

echo '</div>';
echo '<div class="messages sent-messages">';
echo '<h2>Sent Messages:</h2>';
foreach ($messages as $receiver => $messageGroup) {
   if ($receiver === $selected_user && isset($messageGroup['sent'])) {
       foreach ($messageGroup['sent'] as $message_sent) {
           echo "<p class='sent-message'>$message_sent</p>";
       }
   }
}


echo "<div class='one'>";
echo '<textarea name="Message" id="Message" placeholder="Say something" class="comment-textarea"></textarea><br>';
echo '<input type="submit" value=">>" class="submit-button">';
echo '</div>';
echo '</form>';



echo '</div>';
echo '</div>';
}

?>
<?php
    if (isset($_POST['logout'])) {
        // Logout functionality
        session_destroy();
        header("Location: index.php"); 
        ob_end_flush(); // Redirect to your login page
    }
    ?>
  <script>
        window.onload = function() {
            // Scroll to the bottom of the page
            window.scrollTo(0, document.body.scrollHeight);
        }
    </script>
  </body>
</html>
