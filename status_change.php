<?php
session_start();
include 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $taskId = $_POST['task_id'];
    $newStatus = $_POST['status'];
    $userId = $_SESSION['user_id'];

    // update the task status in the database
    $sql = "UPDATE tasks SET status=? WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $newStatus, $taskId, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }

    $stmt->close();
}
