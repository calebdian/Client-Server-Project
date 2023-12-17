<html>
 <head>
  <title>Login Form with data</title> 
  <link rel="stylesheet" href="../Css/work.css"> 
 </head>

  <body>
     <form action="process_login.php" method="post">
      <h1>USER LOGIN </h1>
      <input type="text" name="email" placeholder="Enter your email or UserName"><br>
      <input type="password" name="password" placeholder="Enter your password"><br>
      <input type="submit" value="submit"> 
      <p>Are you new to this platform? <a href="signup.php">Sign Up</a></p>
      <p style="text-align:center;">Are you an admin ? <a href="../AdminPanel/index.php">Login as Admin </a></p>    
     </form>

    
  </body>

</html>