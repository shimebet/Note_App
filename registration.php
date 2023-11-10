<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Registration</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<?php
include('conn/conn.php');

// When form submitted, insert values into the database.
if (isset($_POST['username'])) {
    // removes backslashes
    $username = stripslashes($_POST['username']);
    // escapes special characters in a string
    $username = htmlspecialchars($username);
    $email    = stripslashes($_POST['email']);
    $email    = htmlspecialchars($email);
    $password = stripslashes($_POST['password']);
    $password = htmlspecialchars($password);
    $create_datetime = date("Y-m-d H:i:s");

    // Use prepared statements to prevent SQL injection
    $query = "INSERT INTO users (username, password, email, create_datetime) VALUES (:username, :password, :email, :create_datetime)";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', md5($password));
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':create_datetime', $create_datetime);

    // Execute the query
    $result = $stmt->execute();

    if ($result) {
        echo "<div class='form'>
              <h3>You are registered successfully.</h3><br/>
              <p class='link'>Click here to <a href='login.php'>Login</a></p>
              </div>";
    } else {
        echo "<div class='form'>
              <h3>Required fields are missing.</h3><br/>
              <p class='link'>Click here to <a href='registration.php'>registration</a> again.</p>
              </div>";
    }
} else {
?>
    <form class="form" action="" method="post">
        <h1 class="login-title">Registration</h1>
        <input type="text" class="login-input" name="username" placeholder="Username" required />
        <input type="text" class="login-input" name="email" placeholder="Email Adress">
        <input type="password" class="login-input" name="password" placeholder="Password">
        <input type="submit" name="submit" value="Register" class="login-button">
        <p class="link">Already have an account? <a href="login.php">Login here</a></p>
    </form>
<?php
}
?>
</body>
</html>
