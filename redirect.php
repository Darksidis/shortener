<?php
require_once 'database.php';
require_once 'link.php';


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$db = new Database();
$shortener = new LinkShortener($db);


// // Получить короткий URL-адрес
$short_url = $_GET['short_url'];

// Получить длинный URL-адрес, связанный с коротким URL-адресом
$long_url = $shortener -> get_long_url($short_url);

$shortener -> add_click($short_url);
// Переадресация юзера на длинную ссылку
header("Location: $long_url");
?>
