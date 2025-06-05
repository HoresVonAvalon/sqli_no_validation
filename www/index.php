<?php
// index.php
// Einfaches Login-Formular, das per POST an login.php sendet.

?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
</head>
<body>
  <h1>Login</h1>
  <form action="login.php" method="post">
    <label for="username">Benutzername:</label><br>
    <input type="text" id="username" name="username"><br><br>

    <label for="password">Passwort:</label><br>
    <input type="password" id="password" name="password"><br><br>

    <button type="submit">Einloggen</button>
  </form>
</body>
</html>