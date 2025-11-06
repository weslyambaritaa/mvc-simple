<?php
// model/Database.php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // DSN (Data Source Name) untuk MySQL
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME; // Diubah ke mysql
        
        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS); // User dan Pass disesuaikan
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}