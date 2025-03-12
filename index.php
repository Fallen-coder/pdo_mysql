<?php
// Datubāzes pieslēgšanas informācija
$host = 'localhost'; // vai servera IP, ja tas nav localhost
$dbname = 'blog_12032025';
$username = 'Fallen'; // Aizvietojiet ar savu datubāzes lietotājvārdu
$password = 'password'; // Aizvietojiet ar savu datubāzes paroli

try {
    // Izveidojam PDO savienojumu ar MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Iestatām PDO kļūdu ziņošanas režīmu uz izņēmumiem
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL vaicājums, lai iegūtu visus ierakstus no 'posts' tabulas
    $stmt = $pdo->query("SELECT * FROM posts");

    // Pārbaudām, vai ir iegūti dati
    if ($stmt->rowCount() > 0) {
        // Izdrukājam visus ierakstus
        echo "<h1>Visi raksti no bloga</h1>";
        echo "<ul>";

        // Caur iterāciju izvadām katru ierakstu
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<li>";
            echo "<strong>" . htmlspecialchars($row['title']) . "</strong><br>";
            echo "Autors: " . htmlspecialchars($row['author']) . "<br>";
            echo "Saturs: " . nl2br(htmlspecialchars($row['content'])) . "<br>";
            echo "<em>Izveidots: " . $row['created_at'] . "</em>";

            // Iegūstam komentārus saistītus ar šo rakstu
            $postId = $row['post_id'];
            $commentStmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = :post_id");
            $commentStmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
            $commentStmt->execute();

            // Ja rakstam ir komentāri, tad tos parādām
            if ($commentStmt->rowCount() > 0) {
                echo "<h3>Komentāri:</h3>";
                echo "<ul>";
                while ($comment = $commentStmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<li>";
                    echo "<strong>" . htmlspecialchars($comment['author']) . ":</strong> " . htmlspecialchars($comment['content']);
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Nav komentāru.</p>";
            }

            echo "</li><hr>";
        }
        
        echo "</ul>";
    } else {
        echo "Nav atrasti nekādi ieraksti.";
    }
} catch (PDOException $e) {
    // Ja notiek kļūda savienojumā vai vaicājumā
    echo "Kļūda: " . $e->getMessage();
}
?>

