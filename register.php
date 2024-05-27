<?php 
include 'includes/config.php';

session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $stmt->insert_id;

        // set cookies for user_id and username
        setcookie("user_id", $stmt->insert_id, time() + (86400 * 30), "/"); // 86400 = 1 day
        setcookie("username", $username, time() + (86400 * 30), "/");

        header('Location: main.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <link rel="stylesheet" href="styles/register.css?v=1.0">
</head>
<body>
    <div class="container">
        <h2>Registration</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</body>
</html>