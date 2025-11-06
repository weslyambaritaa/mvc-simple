<?php
// controller/AuthController.php

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Menampilkan halaman login
    public function showLogin() {
        include 'view/login.php';
    }

    // Menampilkan halaman registrasi
    public function showRegister() {
        include 'view/register.php';
    }

    /**
     * Memproses Login (Authentication, State, Session) 
     */
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Keamanan: trim input (Data Integrity) 
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $user = $this->userModel->login($username, $password);

            if ($user) {
                // Autentikasi berhasil: Simpan ke Session 
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama'] = $user['nama']; // Ditambah: simpan nama ke session
                
                // Redirect ke dashboard
                header('Location: index.php?url=auth/dashboard');
                exit;
            } else {
                // Login gagal
                $error = "Username atau password salah.";
                // Kirim error ke view
                include 'view/login.php';
            }
        }
    }

    /**
     * Memproses Registrasi via AJAX 
     */
    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama = trim($_POST['nama']); // Ditambah: ambil nama
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            // Validasi data sederhana (Data Integrity) 
            // Ditambah: validasi $nama
            if (empty($nama) || empty($username) || empty($password)) { 
                $response = ['success' => false, 'message' => 'Nama, username, dan password tidak boleh kosong.'];
            // Panggil model dengan $nama
            } elseif ($this->userModel->register($nama, $username, $password)) { 
                $response = ['success' => true, 'message' => 'Registrasi berhasil! Silakan login.'];
            } else {
                $response = ['success' => false, 'message' => 'Username sudah terdaftar.'];
            }
            
            // Kirim respon sebagai JSON untuk AJAX
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }

    /**
     * Halaman Dashboard (Hello World)
     */
    public function dashboard() {
        // Keamanan: Cek Session 
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?url=auth/showLogin');
            exit;
        }

        // Ambil data user (nama) dari session untuk ditampilkan
        $data = [
            'nama' => $_SESSION['nama'] // Diubah dari username ke nama
        ];
        
        include 'view/dashboard.php';
    }

    /**
     * Fitur Logout (Mengelola State & Session) 
     */
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php?url=auth/showLogin');
        exit;
    }
}