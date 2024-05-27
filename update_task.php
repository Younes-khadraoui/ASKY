<?php
session_start();
include 'includes/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $taskId = $_POST['task_id'];
    $field = $_POST['field'];
    $newValue = $_POST['value'];
    $userId = $_SESSION['user_id'];

    $allowedFields = ['task_name', 'description'];
    if (!in_array($field, $allowedFields)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid field']);
        exit;
    }

    $sql = "UPDATE tasks SET $field = ? WHERE id = ? AND user_id = ?";
    error_log("SQL: $sql");
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("sii", $newValue, $taskId, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update task.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
