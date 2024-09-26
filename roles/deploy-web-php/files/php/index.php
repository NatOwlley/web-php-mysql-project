<?php
$mysqli = new mysqli("db", "user", "password", "notes_app"); 

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $mysqli->prepare("INSERT INTO notes (title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $content);
    $stmt->execute();
    $stmt->close();
}

$result = $mysqli->query("SELECT * FROM notes ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notes App</title>
</head>
<body>
    <h1>Заметки</h1>
    <form method="POST">
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="content" placeholder="Content" required></textarea>
        <button type="submit">Добавить</button>
    </form>

    <h2>Заметки:</h2>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                <small><?php echo $row['created_at']; ?></small>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>

<?php
$mysqli->close();
?>