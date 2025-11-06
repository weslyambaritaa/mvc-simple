<?php
// model/User.php

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Registrasi user baru
     * Menerapkan Encryption (password_hash) 
     * Menerapkan Prepared Statements (Anti-SQL Injection) 
     */
    public function register($username, $password) {
        // Enkripsi password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$username, $hashedPassword]);
            return true;
        } catch (PDOException $e) {
            // Cek jika error karena username sudah ada (unique constraint)
            if ($e->getCode() == 23505) { 
                return false; // Username duplikat
            }
            throw $e;
        }
    }

    /**
     * Login user
     * Menerapkan Authentication 
     */
    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifikasi password 
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Login berhasil
        } else {
            return false; // Login gagal
        }
    }
}