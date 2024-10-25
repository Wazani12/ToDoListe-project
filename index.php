<?php
include 'database.php'; // Include the database connection file

// Adding a new task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    $task_name = $_POST['taskInput'];
    if (!empty($task_name)) {
        $sql = "INSERT INTO tasks (task_name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $task_name);
        $stmt->execute();
        $stmt->close();
    }
}

// Marking a task as completed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_task'])) {
    $task_id = $_POST['task_id'];
    $sql = "UPDATE tasks SET is_completed = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $stmt->close();
}

// Deleting a task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_task'])) {
    $task_id = $_POST['task_id'];
    $sql = "DELETE FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $stmt->close();
}

// Fetching tasks
$open_tasks = $conn->query('SELECT * FROM tasks WHERE is_completed = 0');
$closed_tasks = $conn->query('SELECT * FROM tasks WHERE is_completed = 1');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TO DO LIST</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>TO DO LIST</h1>
        <p>Manage your tasks efficiently</p>

        <!-- Form to add a task -->
        <form class="in" action="index.php" method="POST">
            <div class="input-group">
                <input type="text" name="taskInput" placeholder="Add a task" required>
                <button type="submit" name="add_task">Add</button>
            </div>
        </form>

        <div class="row">
            <!-- Open Tasks -->
            <div class="Tasks">
                <h2>Open Tasks</h2>
                <?php if ($open_tasks->num_rows > 0): ?>
                    <ul>
                        <?php while ($task = $open_tasks->fetch_assoc()): ?>
                            <li>
                                <?= htmlspecialchars($task['task_name']); ?>
                                <div class="task">
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="task_id" value="<?= $task['id']; ?>">
                                        <button type="submit" name="complete_task">Finished</button>
                                        <button type="submit" name="delete_task">Delete</button>
                                    </form>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>No open tasks!</p>
                <?php endif; ?>
            </div>

            <!-- Closed Tasks -->
            <div class="Tasks">
                <h2>Closed Tasks</h2>
                <?php if ($closed_tasks->num_rows > 0): ?>
                    <ul>
                        <?php while ($task = $closed_tasks->fetch_assoc()): ?>
                            <li>
                                <?= htmlspecialchars($task['task_name']); ?>
                                <div class="task">
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="task_id" value="<?= $task['id']; ?>">
                                        <button type="submit" name="delete_task">Delete</button>
                                    </form>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>No closed tasks!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
