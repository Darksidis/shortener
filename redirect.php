<?php
require_once 'database.php';
require_once 'link.php';


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$db = new Database();
$shortener = new LinkShortener($db);


// Retrieve the short URL from the URL
$short_url = $_GET['short_url'];

// Retrieve the long URL associated with the short URL
$long_url = $shortener -> get_long_url($short_url);

$shortener -> add_click($short_url);
// Redirect the user to the long URL
header("Location: $long_url");
?>
