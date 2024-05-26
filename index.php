<?php
include 'includes/config.php';

session_start();

if (isset($_COOKIE['user'])) {
    $identifier = $_COOKIE['user'];

    $sql = "SELECT * FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $found_user = $result->fetch_assoc();
        $_SESSION['user_id'] = $found_user['id'];
        $_SESSION['username'] = $found_user['username']; 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="./styles/style.css"?v=1.0>
</head>
<body>
    <div class='container'>
        <h1>Welcome to Your Todo app</h1>
        <p>Discover amazing features and organize your life!</p>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="todo.php" class="button">Go to Todo List</a>
        <?php else: ?>
            <a href="register.php" class="button">Register</a>
            <a href="login.php" class="button">Login</a>
        <?php endif; ?>
    </div>
</body>
</html>