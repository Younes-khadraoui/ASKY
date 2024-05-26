<?php
session_start();
include 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $taskId = $_POST['task_id'];
    $userId = $_SESSION['user_id'];

    $sql = "DELETE FROM tasks WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $taskId, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Task deleted";
    } else {
        echo "Error deleting task";
    }
}
?>
