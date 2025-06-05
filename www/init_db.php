<?php
// init_db.php
// Dieses Skript legt die SQLite-Datenbank "users.db" an und
// erstellt eine Beispiel-Tabelle "users" mit einem Test-Benutzer.

$dbFile = __DIR__ . '/data/users.db';

// Falls die DB schon existiert, abbrechen
if (file_exists($dbFile)) {
    echo "Die Datenbank existiert bereits unter: $dbFile\n";
    exit;
}

// Stelle sicher, dass das Verzeichnis "data" existiert
if (!is_dir(__DIR__ . '/data')) {
    mkdir(__DIR__ . '/data', 0755, true);
}

// Öffne / erstelle SQLite-DB
try {
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Erstelle Tabelle "users"
    $sql = "
      CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL
      );
    ";
    $db->exec($sql);

    // Beispiel-Benutzer einfügen (username: "test", password: "secret")
    $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (:u, :p)');
    $stmt->execute([
        ':u' => 'test',
        // Hinweis: hier nur als Lehrbeispiel im Klartext.
        // In der Praxis immer Hashes nutzen!
        ':p' => 'secret'
    ]);

     // Beispiel-Benutzer einfügen (username: "test", password: "secret")
    $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (:u, :p)');
    $stmt->execute([
        ':u' => 'evait',
        // Hinweis: hier nur als Lehrbeispiel im Klartext.
        // In der Praxis immer Hashes nutzen!
        ':p' => 'passport'
    ]);


    echo "Datenbank und Tabelle erfolgreich erstellt. Beispiel-User: test / secret\n";
} catch (Exception $e) {
    echo "Fehler beim Erstellen der DB: " . $e->getMessage() . "\n";
    exit(1);
}
