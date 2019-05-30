<?php

class Database {
    private $settings;
    private $connection;

    // Database connection
    public function __construct() {
        $this->settings = parse_ini_file("core/config/connections.ini"); // get settings from .ini config file

        $dsn = "mysql:host=".$this->settings['host'].";dbname=".$this->settings['dbname'].";charset=".$this->settings['charset']; 
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        $this->connection = new PDO($dsn, $this->settings['user'], $this->settings['password'], $options);
    }

    // query function
    public function query($query, $params = array()) {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }
}

?>