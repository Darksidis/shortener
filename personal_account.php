<!-- Personal Account Page -->
<html>
<head>
  <title>My Personal Account</title>
</head>
<body>
<!-- Проверить, вошел ли пользователь в систему, и отобразить информацию о личной учетной записи. -->
<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

      session_start();
      if (isset($_SESSION["user_id"])) {
        // Запрос к базе данных для получения статистики переходов по ссылкам
        include "database.php";
        include "link.php";

        $db = new Database();
        $linkShortener = new LinkShortener($db);

$username = $_SESSION["username"];
$result = $linkShortener ->get_statistics($username);

// Отображение статистики по переходам
echo "<h1>Welcome, " . $username . "</h1>";
echo "<h2>Statistics on your links:</h2>";
echo "<table>";
  echo "<tr><th>Link</th><th>Clicks</th></tr>";
  while ($row = $result->fetch_assoc()) {
  echo "<tr><td>" . $row["short_url"] . "</td><td>" . $row["clicks"] . "</td></tr>";
  }
  echo "</table>";
} else {
// Перенаправить пользователя на страницу входа, если он не авторизован
header("Location: login.html");
}
?>
</body>
</html>