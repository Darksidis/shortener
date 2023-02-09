<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" >
</head>
<body>
<div id="header">
    <p id="welcome">Welcome, <?php
        session_start();
        echo $_SESSION['username'] ?></p>
</div>


<div id="shortenerForm">
    <form name="form">
        <label for="longUrl"></label><input type="text" id="longUrl" name="longUrl" placeholder="Enter your long URL">
        <button id="submitButton" type="submit">Shorten </button>
    </form>
</div>


<div id="linksList">
    <p>Links List:</p>
    <ul>
        <?php

        include "link.php";
        include "database.php";

        $db = new Database();
        $linkShortener = new LinkShortener($db);
        $links = $linkShortener -> show_links();
        $server_name = $_SERVER['SERVER_NAME'];
        foreach ($links as $link) { ?>

            <li><a href="<?php echo $link['short_url'];?>"><?php echo $link['short_url']; ?></a></li>
        <?php } ?>
    </ul>
</div>

<div style="display: flex;">
    <form action="login.php" method="post">
        <input style="right;" type="submit" name="logout" value="Logout">
    </form>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input style="left;" type="submit" name="clear_links" value="clear links">
    </form>
</div>

</body>

<script>
    document.getElementById("submitButton").addEventListener("click", function(){
        const longUrl = document.getElementById("longUrl").value;
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                const shortUrl = this.responseText;
                const linksList = document.getElementById("linksList");
                const shortUrlElement = document.getElementById("shortUrl");
                shortUrlElement.innerHTML = shortUrl;
                linksList.innerHTML += "<li><a href='" + shortUrl + "'>" + shortUrl + "</a></li>";
            }
        };
        xhr.send("longUrl=" + longUrl);
    });
</script>
</html>


<?php
require_once 'database.php';
require_once 'link.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$db = new Database();
$shortener = new LinkShortener($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['clear_links'])) {
        $shortener -> clear_links();
    } else {
        $username = "";
        session_start();
        if (isset($_SESSION["user_id"])) {
            // если юзер авторизован, то переменной присваивается его юзернейм
            $username = $_SESSION["username"];
        }
        echo $username;
        $longLink = $_POST["longUrl"];

        $shortLink = $shortener->shorten($longLink, $username);
    }




}
