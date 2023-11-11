<?php
include('conn/conn.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        echo "<script>alert('Username and password are required. Please enter both.');</script>";
    } else {
        $username = stripslashes($_POST['username']);
        $username = htmlspecialchars($username);
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
            exit(); // Ensure no further code is executed after the redirect
        } else {
            echo "<script>alert('Incorrect Username or Password. Please enter correct credentials.');</script>";
        }
    }
}
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
        <p class="link">Don't have an account? <a href="registration.php">Register Now</a></p>
    </form>
</body>
</html>
 