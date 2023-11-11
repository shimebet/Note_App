<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Registration</title>
    <link rel="stylesheet" href="style.css"/>
    <style>
        .success-popup {
            color: green;
        }

        .error-popup {
            color: red;
        }
    </style>
</head>
<body>

<?php
include('conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate username
    if (strlen($username) < 4 || strlen($username) > 15) {
        echo "<p class='error-popup'>Username must be between 4 and 15 characters.</p>";
    }
    // Validate password
    elseif (strlen($password) <= 4 || !preg_match('/^(?=.*[a-zA-Z])(?=.*\d).+$/', $password)) {
        echo "<p class='error-popup'>Password must be greater than 4 characters and contain a combination of letters and numbers.</p>";
    }
    // Validate email existence
    elseif (emailExists($conn, $email)) {
        echo "<p class='error-popup'>An account with this email already exists. Please login or use a different email.</p>";
    } else {
        // Prepared statement to prevent SQL injection
        $query = "INSERT INTO users (username, password, email, create_datetime) VALUES (:username, :password, :email, NOW())";
        $stmt = $conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', md5($password));
        $stmt->bindParam(':email', $email);

        // Execute the query
        $result = $stmt->execute();

        // Display a popup message based on the result
        echo "<script>
            if ($result) {
                alert('Registration successful');
                window.location.href = 'registration.php';
            } else {
                alert('Registration failed. Please check your input and try again.');
            }
          </script>";
    }
}

function emailExists($conn, $email) {
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}
?>

<form class="form" action="" method="post">
    <h1 class="login-title">Registration</h1>
    <input type="text" class="login-input" name="username" placeholder="Username" required />
    <input type="text" class="login-input" name="email" placeholder="Email Address">
    <input type="password" class="login-input" name="password" placeholder="Password">
    <input type="submit" name="submit" value="Register" class="login-button">
    <p class="link">Already have an account? <a href="index.php">Login here</a></p>
</form>

</body>
</html>
