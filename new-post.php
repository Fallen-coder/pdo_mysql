<?php
// Database connection details
$host = 'localhost'; // your database host
$dbname = 'blog_12032025';
$username = 'Fallen'; // your database username
$password = 'password'; // your database password

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];

    // Debugging: output form data (optional)
    echo "Title: $title<br>";
    echo "Content: $content<br>";
    echo "Author: $author<br>";

    try {
        // Create a PDO connection
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL query to insert a new post into the database
        $sql = "INSERT INTO posts (title, content, author, created_at) VALUES (:title, :content, :author, NOW())";

        // Prepare and execute the query
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':author', $author);

        // Execute the query
        $stmt->execute();

        // Success message
        echo "New post added successfully!";
        echo "<br><br><a href='index.php'>Go back to blog</a>";
        exit(); // Stop further execution after adding the post

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        echo "<br>SQLSTATE: " . $e->getCode();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Post</title>
</head>
<body>

<h2>Add New Post</h2>

<!-- HTML Form to add a new post -->
<form action="new-post.php" method="POST">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" required><br><br>

    <label for="content">Content:</label>
    <textarea id="content" name="content" rows="5" cols="40" required></textarea><br><br>

    <label for="author">Author:</label>
    <input type="text" id="author" name="author" required><br><br>

    <button type="submit">Add Post</button>
</form>

</body>
</html>
