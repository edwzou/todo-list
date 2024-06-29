<?php
require_once 'config.php';

// Database configuration
$servername = $db_config['servername'];
$username = $db_config['username'];
$password = $db_config['password'];
$dbname = $db_config['dbname'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission to add new task
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['task'])) {
    $task = $conn->real_escape_string($_POST['task']);
    $conn->query("INSERT INTO tasks (task) VALUES ('$task')");
}

// Process deletion of tasks
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM tasks WHERE id=$id");
}

// Retrieve tasks from database
$result = $conn->query("SELECT * FROM tasks ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <style>
        /* Optional: Add some basic CSS for styling */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>To-Do List</h1>

    <!-- Form to add new task -->
    <form method="POST" action="">
        <input type="text" name="task" placeholder="Enter new task" required>
        <button type="submit">Add Task</button>
    </form>

    <!-- List of tasks -->
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <?php echo htmlspecialchars($row['task']); ?>
                <a href="?delete=<?php echo $row['id']; ?>">Delete</a>
            </li>
        <?php endwhile; ?>
    </ul>

    <?php $conn->close(); ?>
</body>
</html>

