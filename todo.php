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
                <p class="name">Amina Belkacem</p>
                <p class="hello">Hello, Amina</p>
            </div>
        </div>
        <button class="logout">Log out</button>
    </header>

    <div class="container">
        <div class="search-bar">
            <input type="text" id="task-input" placeholder="i mean here" onclick="toggleDropdown()">
            <button class="add-btn" onclick="toggleDropdown()">+</button>
            <div class="dropdown" id="dropdown">
                <form action="index.php" method="post">
                    <input type="text" id="task-name" name="task_name" placeholder="Task Name" required>
                    <input type="text" id="task-desc" name="task_desc" placeholder="Task Description" required>
                    <select id="task-state" name="task_state" required>
                        <option value="todo">To Do</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="done">Done</option>
                    </select>
                    <button type="submit" class="submit-btn">Add Task</button>
                </form>
            </div>
        </div>

        <div class="task-list" id="task-list">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $taskName = $_POST['task_name'];
                $taskDesc = $_POST['task_desc'];
                $taskState = $_POST['task_state'];

                if (!empty($taskName) && !empty($taskDesc)) {
                    echo '<div class="task-item">';
                    echo '<div class="task-header">';
                    echo '<p class="task-title">' . htmlspecialchars($taskName) . '</p>';
                    echo '<button class="task-status ' . htmlspecialchars($taskState) . '" onclick="changeStatus(this)">' . ucfirst($taskState) . '</button>';
                    echo '<button class="remove-btn" onclick="removeTask(this)">X</button>';
                    echo '</div>';
                    echo '<p class="task-description">' . htmlspecialchars($taskDesc) . '</p>';
                    echo '</div>';
                }
            }
            ?>
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
                    newState = 'ongoing';
                    break;
                case 'ongoing':
                    newState = 'done';
                    break;
                case 'done':
                    newState = 'todo';
                    break;
            }
            button.innerText = newState.charAt(0).toUpperCase() + newState.slice(1);
            button.className = 'task-status ' + newState;
        }

        function removeTask(button) {
            button.parentElement.parentElement.remove();
        }
    </script>
</body>
</html>
