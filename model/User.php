<?php
// model/User.php

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Registrasi user baru
     * Menggunakan password_hash() untuk keamanan
     * Menggunakan prepared statement untuk mencegah SQL injection
     */
    public function register($nama, $username, $password) {
        // Enkripsi password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // SQL untuk menambah user baru
        $sql = "INSERT INTO users (nama, username, password) VALUES (?, ?, ?)";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$nama, $username, $hashedPassword]);
            return true;
        } catch (PDOException $e) {
            // Tangani duplikat username (error code 1062 untuk MySQL)
            if ($e->getCode() == 23000 || $e->getCode() == 1062) {
                return false; // username sudah terdaftar
            }
            throw $e; // error lain dilempar ke atas
        }
    }

    /**
     * Login user
     * Verifikasi password dengan password_verify()
     */
    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Pastikan data user ditemukan dan kolom password ada
        if ($user && isset($user['password']) && !empty($user['password'])) {
            // Verifikasi password hash
            if (password_verify($password, $user['password'])) {
                return $user; // Login sukses, kembalikan data user
            } else {
                return false; // Password salah
            }
        }

        return false; // Username tidak ditemukan atau kolom password tidak valid
    }
}
