<?php
class LinkShortener
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
        $this -> create_table_links_or_do_nothing ();
    }

    public function shorten($url, $username)
    {   // функция по сокращению ссылок

        $conn = $this->db->getConnection();

        // Проверить, есть ли сокращеннная ссылка уже в базе

        $existing = $conn->query("SELECT short_url FROM links WHERE url = '$url'")->fetch_assoc();
        if ($existing) {
            return $existing['short_url'];
        }

        // Сгенерировать уникальную сокращенную ссылку
        $short_url = $this->generateShortUrl();
        $existing = $conn->query("SELECT short_url FROM links WHERE short_url = '$short_url'")->fetch_assoc();
        while ($existing) {
            $short_url = $this->generateShortUrl();
            $existing = $conn->query("SELECT short_url FROM links WHERE short_url = '$short_url'")->fetch_assoc();
        }

        // Добавить ссылку в базу данных
        $conn->query("INSERT INTO links (url, short_url, username) VALUES ('$url', '$short_url', '$username')");

        return $short_url;
    }

    public function show_links ()
    {   // функция по отображению ссылок

        $conn = $this->db->getConnection();

        return $conn->query('SELECT short_url FROM `links` ORDER BY id');
    }

    public function add_click ($short_url)
    {   // добавляет клики при переходе по ссылке

        $conn = $this->db->getConnection();
        $clicks = $conn->query("SELECT clicks FROM links WHERE short_url = '$short_url'")->fetch_assoc();
        $clicks = (int)$clicks["clicks"];
        $clicks += 1;
        $conn->query("UPDATE links SET clicks = '$clicks' WHERE short_url = '$short_url'");



    }

    public function get_statistics ($username)
    {   // отображение статистики по переходам

        $conn = $this->db->getConnection();

        $sql = "SELECT * FROM links WHERE username='$username'";

        return $conn->query($sql);;

    }

    public function get_long_url ($short_url)
    {   // получение длинной ссылки по сокращенной

        $conn = $this->db->getConnection();
        $result = $conn->query("SELECT url FROM links WHERE short_url = '$short_url'");
        $row = $result->fetch_assoc();

        return $row['url'];
    }

    public function clear_links () {
        $conn = $this->db->getConnection();
        $conn->query("DELETE FROM `links`;");
    }

    public function create_table_links_or_do_nothing ()
    {   // создает таблицу links

        $conn = $this->db->getConnection();

        $sql = "CREATE TABLE IF NOT EXISTS `links` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `short_url` VARCHAR(255) NOT NULL UNIQUE,
    `url` VARCHAR(255) NOT NULL,
    `username` VARCHAR(255) NULL,
    `clicks` INTEGER (255) NULL
);";

        $result = $conn->query($sql);

        if ($result) {
            return "Tables created successfully";
        } else {
    return "Error creating tables: " . $conn->error;
}

}



    private function generateShortUrl()
    {   // Генерация рандомной строки для сокращенной сссылки

        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(6/strlen($x)))),1,6);
    }
}

