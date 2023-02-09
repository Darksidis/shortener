<?php

class User {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database;
        $this -> create_table_users_or_do_nothing();
    }

    public function register($username, $password)
    {   // регистрация

        // хеширование пароля для безопасности
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // создаем запрос для добавления пользователя в базу данных
        $query = "INSERT INTO users (username, password) VALUES (?, ?)";

        // получить соединение из класса базы данных и выполнить запрос
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $passwordHash);
        $stmt->execute();
    }

    public function login($username, $password)
    {   // авторизация

        // создать запрос, чтобы получить пользователя из базы данных
        $query = "SELECT * FROM users WHERE username = ?";

        // получить соединение из класса базы данных и выполнить запрос
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // проверка пароля на корректность
        if ($user && password_verify($password, $user['password'])) {
            // начинаем сеанс и сохраняем идентификатор пользователя в сеансе
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: personal_account.php");
            return true;
        } else {
            return false;
        }
    }

    public function create_table_users_or_do_nothing()
    {    // создает таблицу users

        $conn = $this->db->getConnection();


        $sql = "CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL
);";

        $result = $conn->query($sql);

        if ($result) {
            return "Tables created successfully";
        } else {
            return "Error creating tables: " . $this->conn->error;
        }
    }

    public function logout() {
        // разлогинивание

        // уничтожить текущую сессию
        session_start();
        session_destroy();
    }
}