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
    public function register($nama, $username, $password) { // Ditambah $nama
        // Enkripsi password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // SQL diubah untuk menyertakan 'nama'
        $sql = "INSERT INTO users (nama, username, password) VALUES (?, ?, ?)"; 
        try {
            $stmt = $this->db->prepare($sql);
            // Eksekusi diubah untuk menyertakan $nama
            $stmt->execute([$nama, $username, $hashedPassword]); 
            return true;
        } catch (PDOException $e) {
            // Cek jika error karena username sudah ada (unique constraint)
            // Kode error 1062 spesifik untuk MySQL duplicate entry
            if ($e->getCode() == 23000 || $e->getCode() == 1062) { 
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
        // SELECT * sudah mengambil semua kolom (termasuk 'nama')
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