<?php 
session_start();
include 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Task submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $taskName = $_POST['task_name'];
    $taskDesc = $_POST['task_desc'];
    $taskState = $_POST['task_state'];
    $userId = $_SESSION['user_id'];

    if (!empty($taskName) && !empty($taskDesc)) {
        $sql = "INSERT INTO tasks (task_name, description, status, user_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $taskName, $taskDesc, $taskState, $userId);
        $stmt->execute();
    }
}

// Retrieving tasks from the database
$sql = "SELECT * FROM tasks WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link rel="stylesheet" href="styles/todo.css?v=1.0">
</head>
<body>
    <header class="navbar">
        <div class="profile">
            <img src="avatar.jpg" alt="Avatar" class="avatar">
            <div class="greeting">
                <p class="name"><?php echo $_SESSION['username']; ?></p>
                <p class="hello">Hello, <?php echo $_SESSION['username']; ?></p>
            </div>
        </div>
        <form action="logout.php" method="post">
            <button type="submit" class="logout">Log out</button>
        </form>
    </header>

    <div class="container">
        <div class="search-bar">
            <input type="text" id="task-input" placeholder="Add A Task" onclick="toggleDropdown()">
            <button class="add-btn" onclick="toggleDropdown()">+</button>
            <div class="dropdown" id="dropdown">
                <form action="todo.php" method="post">
                    <input type="text" id="task-name" name="task_name" placeholder="Task Name" required>
                    <input type="text" id="task-desc" name="task_desc" placeholder="Task Description" required>
                    <select id="task-state" name="task_state" required>
                        <option value="todo">To Do</option>
                        <option value="doing">Doing</option>
                        <option value="done">Done</option>
                    </select>
                    <button type="submit" class="submit-btn">Save Task</button>
                </form>
            </div>
        </div>

        <div class="task-list" id="task-list">
            <?php foreach ($tasks as $task): ?>
                <div class="task-item">
                    <div class="task-header">
                        <p class="task-title"><?php echo htmlspecialchars($task['task_name']); ?></p>
                        <p class="task-title"><?php echo htmlspecialchars($task['description']); ?></p>
                        <button class="task-status <?php echo htmlspecialchars($task['status']); ?>" onclick="changeStatus(this)"><?php echo ucfirst($task['status']); ?></button>
                        <button class="remove-btn" onclick="removeTask(this, <?php echo $task['id']; ?>)">X</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown');
            dropdown.style.display = dropdown.style.display === 'none' || dropdown.style.display === '' ? 'flex' : 'none';
        }

        function changeStatus(button) {
            const currentState = button.innerText.toLowerCase();
            let newState;
            switch (currentState) {
                case 'todo':
                    newState = 'doing';
                    break;
                case 'doing':
                    newState = 'done';
                    break;
                case 'done':
                    newState = 'todo';
                    break;
            }
            button.innerText = newState.charAt(0).toUpperCase() + newState.slice(1);
            button.className = 'task-status ' + newState;
        }

        function removeTask(button, taskId) {
            const taskItem = button.parentElement.parentElement;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'remove_task.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    taskItem.remove();
                }
            };
            xhr.send('task_id=' + taskId);
        }
    </script>
</body>
</html>
