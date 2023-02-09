<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Форма регистраций</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" >
</head>

<body>

<div class="container mt-4">

    <h1> Форма регистраций</h1>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" class="form-control" name="username"
               id="username" placeholder="Введите логин">
        <input type="text" class="form-control" name="password"
               id="password" placeholder="Введите пароль">
        <button class="btn btn-succes"> Зарегистрироваться </button>
    </form>
</div>

</body>
</html>



<?php
require_once 'database.php';
require_once 'user.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$db = new Database();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $user->register($username, $password);
}