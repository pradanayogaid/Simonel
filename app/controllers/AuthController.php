<?php

class AuthController extends Controller {

    public function index() {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        $data['title'] = 'Login';
        // Check for flash messages
        $data['error'] = isset($_SESSION['error']) ? $_SESSION['error'] : '';
        unset($_SESSION['error']);
        
        $this->view('layouts/header', $data);
        $this->view('auth/login', $data);
        $this->view('layouts/footer', $data);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Email dan Password harus diisi.';
                header('Location: ' . BASEURL . '/auth');
                exit;
            }

            $userModel = $this->model('User');
            $user = $userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Set Session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
                header('Location: ' . BASEURL . '/dashboard');
                exit;
            } else {
                $_SESSION['error'] = 'Email atau Password salah.';
                header('Location: ' . BASEURL . '/auth');
                exit;
            }
        } else {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function register() {
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        $data['title'] = 'Register';
        $data['error'] = isset($_SESSION['error']) ? $_SESSION['error'] : '';
        unset($_SESSION['error']);
        
        $this->view('layouts/header', $data);
        $this->view('auth/register', $data);
        $this->view('layouts/footer', $data);
    }

    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';

            if (empty($name) || empty($email) || empty($password) || empty($password_confirm)) {
                $_SESSION['error'] = 'Semua field harus diisi.';
                header('Location: ' . BASEURL . '/auth/register');
                exit;
            }

            if ($password !== $password_confirm) {
                $_SESSION['error'] = 'Password tidak cocok.';
                header('Location: ' . BASEURL . '/auth/register');
                exit;
            }

            $userModel = $this->model('User');
            
            // Check if email already exists
            if ($userModel->getUserByEmail($email)) {
                $_SESSION['error'] = 'Email sudah terdaftar.';
                header('Location: ' . BASEURL . '/auth/register');
                exit;
            }

            $data = [
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => 'user'
            ];

            if ($userModel->register($data) > 0) {
                $_SESSION['error'] = 'Registrasi berhasil. Silakan login.';
                header('Location: ' . BASEURL . '/auth');
                exit;
            } else {
                $_SESSION['error'] = 'Gagal melakukan registrasi.';
                header('Location: ' . BASEURL . '/auth/register');
                exit;
            }
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASEURL . '/auth');
        exit;
    }
}
