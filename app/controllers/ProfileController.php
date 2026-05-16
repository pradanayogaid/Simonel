<?php

class ProfileController extends Controller {
    public function __construct() {
    }

    public function index() {
        $userModel = $this->model('User');
        $data['title'] = 'My Account';
        $data['user'] = $userModel->getUserById($_SESSION['user']['id']);
        
        $data['success'] = isset($_SESSION['success']) ? $_SESSION['success'] : '';
        $data['error'] = isset($_SESSION['error']) ? $_SESSION['error'] : '';
        unset($_SESSION['success'], $_SESSION['error']);

        $this->view('layouts/header', $data);
        $this->view('profile/index', $data);
        $this->view('layouts/footer', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!verify_csrf_token()) {
                $_SESSION['error'] = 'Sesi form tidak valid. Silakan coba lagi.';
                header('Location: ' . BASEURL . '/profile');
                exit;
            }

            $userModel = $this->model('User');
            $id = $_SESSION['user']['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];

            if ($userModel->updateProfile($id, $name, $email) >= 0) {
                // Update session
                $_SESSION['user']['name'] = $name;
                $_SESSION['user']['email'] = $email;
                $_SESSION['success'] = 'Profil berhasil diperbarui.';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui profil.';
            }
            header('Location: ' . BASEURL . '/profile');
            exit;
        }
    }

    public function password() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!verify_csrf_token()) {
                $_SESSION['error'] = 'Sesi form tidak valid. Silakan coba lagi.';
                header('Location: ' . BASEURL . '/profile');
                exit;
            }

            $userModel = $this->model('User');
            $id = $_SESSION['user']['id'];
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            $user = $userModel->getUserById($id);

            if (!password_verify($current_password, $user['password'])) {
                $_SESSION['error'] = 'Password saat ini salah.';
                header('Location: ' . BASEURL . '/profile');
                exit;
            }

            if ($new_password !== $confirm_password) {
                $_SESSION['error'] = 'Konfirmasi password baru tidak cocok.';
                header('Location: ' . BASEURL . '/profile');
                exit;
            }

            if ($userModel->updatePassword($id, $new_password) > 0) {
                $_SESSION['success'] = 'Password berhasil diubah.';
            } else {
                $_SESSION['error'] = 'Gagal mengubah password.';
            }
            header('Location: ' . BASEURL . '/profile');
            exit;
        }
    }
}
