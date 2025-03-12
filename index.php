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

    // SQL vaicājums, lai iegūtu visus rakstus un to komentārus, izmantojot LEFT JOIN
    $stmt = $pdo->query("
        SELECT posts.*, comments.author AS comment_author, comments.content AS comment_content
        FROM posts
        LEFT JOIN comments ON posts.post_id = comments.post_id
    ");

    // Pārbaudām, vai ir iegūti dati
    if ($stmt->rowCount() > 0) {
        // Izdrukājam visus ierakstus no posts tabulas
        echo "<h1>Visi raksti no bloga</h1>";
        echo "<hr>"; // Horizontāla līnija starp virsrakstu un rakstiem
        echo "<ul>"; // Sākam sarakstu ar rakstiem

     

        // Caur iterāciju izvadām katru ierakstu
        $previousPostId = null;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Ja raksta ID mainās, tad parādām jaunu rakstu
            if ($previousPostId !== $row['post_id']) {
                if ($previousPostId !== null) {
                    // Slēdzam iepriekšējo komentāru sarakstu un pievienojam horizontālo līniju
                    echo "</ul>";
                    echo "<hr>"; // Horizontāla līnija starp rakstiem
                }

                // Ja ir jauns raksts, sākam jaunu sarakstu un parādām raksta informāciju
                echo "<li>";
                echo "<strong>" . htmlspecialchars($row['title']) . "</strong><br>";
                echo "Autors: " . htmlspecialchars($row['author']) . "<br>";
                echo "Saturs: " . nl2br(htmlspecialchars($row['content'])) . "<br>";
                echo "<em>Izveidots: " . $row['created_at'] . "</em><br>";

                // Ja rakstam ir komentāri, sākam jaunu komentāru sarakstu
                echo "<h3>Komentāri:</h3>";
                echo "<ul>";
            }

            // Iegūstam komentārus, ja tie ir pieejami
            if ($row['comment_author'] && $row['comment_content']) {
                echo "<li>";
                echo "<strong>" . htmlspecialchars($row['comment_author']) . ":</strong> " . htmlspecialchars($row['comment_content']);
                echo "</li>";
            }

            // Saglabājam pašreizējo raksta ID, lai varētu pārbaudīt nākamo rakstu
            $previousPostId = $row['post_id'];
        }

        // Slēdzam pēdējo komentāru sarakstu un rakstu
        echo "</ul>"; // Slēdzam pēdējo komentāru sarakstu
        echo "</li>"; // Slēdzam pēdējo rakstu
    } else {
        echo "Nav atrasti nekādi ieraksti.";
    }
} catch (PDOException $e) {
    // Ja notiek kļūda savienojumā vai vaicājumā
    echo "Kļūda: " . $e->getMessage();
}
?>
