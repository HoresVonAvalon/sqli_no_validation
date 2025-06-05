<?php
// login.php
// Liest POST-Daten aus, verbindet sich mit SQLite und überprüft Username/Passwort.
// Je nach ENV-Variable VALIDATION==true wird minimal validiert (z.B. Nonempty, keine Sonderzeichen),
// bei VALIDATION==false wird direkt an die DB übergeben (SQL-Injection-Gefahr!).

// Fehlerberichterstattung einschalten (zu Lehrzwecken)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ENV-Variable auslesen
$validation = getenv('VALIDATION'); // "true" oder "false"

// Verknüpfung zur SQLite-DB
$dbFile = __DIR__ . '/data/users.db';
if (!file_exists($dbFile)) {
    die("Datenbank nicht gefunden. Bitte vorher init_db.php aufrufen.");
}

try {
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("DB-Fehler: " . $e->getMessage());
}

// Lese Formulardaten
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($validation === 'true') {
    // === MIT Validation ===
    // Beispiel: Username/Passwort dürfen nicht leer sein, keine Sonderzeichen außer Buchstaben/Ziffern
    if (empty($username) || empty($password)) {
        die("Bei der Validierung fehlgeschlagen: Benutzername und Passwort dürfen nicht leer sein.");
    }
    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        die("Bei der Validierung fehlgeschlagen: Ungültige Zeichen im Benutzernamen.");
    }
    // Im echten Projekt: Passwort streng geprüft, Hashing, etc.
    // Danach sichere Abfrage mit Prepared Statement
    $stmt = $db->prepare('SELECT password FROM users WHERE username = :u');
    $stmt->execute([':u' => $username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        die("Kein Benutzer mit diesem Namen gefunden.");
    }
    // Für Demo: Klartext-Vergleich (NICHT PRODUKTIV!)
    if ($password === $row['password']) {
        echo "<h2>Erfolgreich eingeloggt (mit Validierung)!</h2>";
        echo "<p>Willkommen, " . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . ".</p>";
    } else {
        die("Passwort nicht korrekt.");
    }
} else {
    // === OHNE Validation (unsicher!) ===
    // ACHTUNG: hier passiert KEINE Überprüfung, POTENTIELL SQL-INJECTION
    $sql = "SELECT password FROM users WHERE username = '$username'";
    echo "<p>$sql</p>";
    try {
        $row = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die("DB-Fehler: " . $e->getMessage());
    }
    if (!$row) {
        die("Kein Benutzer mit diesem Namen gefunden.");
    }
    if ($password === $row['password']) {
        echo "<h2>Erfolgreich eingeloggt (ohne Validierung)!</h2>";
        echo "<p>Willkommen, " . htmlentities($username) . ".</p>";
    } else {
        die("Passwort nicht korrekt.");
    }
}
