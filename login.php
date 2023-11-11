<?php
include('conn/conn.php');
session_start();

// When form submitted, check and create user session.
if (isset($_POST['username'])) {
    $username = stripslashes($_POST['username']); // removes backslashes
    $username = htmlspecialchars($username); // convert special characters to HTML entities
    $password = stripslashes($_POST['password']);
    $password = htmlspecialchars($password);

    // Check user is exist in the database
    $query = "SELECT * FROM `users` WHERE username=:username AND password=:password";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', md5($password));

    // Execute the query
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $_SESSION['username'] = $username;
        // Redirect to user dashboard page
        header("Location: index2.php");
    } else {
        echo "<div class='form'>
              <h3>Incorrect Username/password.</h3><br/>
              <p class='link'>Click here to <a href='login.php'>Login</a> again.</p>
              </div>";
    }
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Login</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
    <form class="form" method="post" name="login">
        <h1 class="login-title">Login</h1>
        <input type="text" class="login-input" name="username" placeholder="Username" autofocus="true"/>
        <input type="password" class="login-input" name="password" placeholder="Password"/>
        <input type="submit" value="Login" name="submit" class="login-button"/>
        <p class="link">Don't have an account? <a href="registration.php">Registration Now</a></p>
    </form>
</body>
</html>
<?php
}
?>
