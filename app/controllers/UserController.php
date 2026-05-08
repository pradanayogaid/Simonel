<?php

class UserController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        // Only Admin can access User Management
        if ($_SESSION['user']['role'] !== 'admin') {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }
    }

    public function index() {
        $userModel = $this->model('User');
        $data['title'] = 'User Management';
        $data['users'] = $userModel->getOnlyStandardUsers(); // Only show 'user' role
        $data['user'] = $_SESSION['user'];

        $this->view('layouts/header', $data);
        $this->view('users/index', $data);
        $this->view('layouts/footer', $data);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            
            // Force role to 'user' for safety if needed, 
            // but the user might want to add another admin? 
            // No, user said "admin hanya bisa memanajemen user saja"
            // So we'll force 'user' role for new entries here.
            
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'role' => 'user' // Forced to user
            ];

            if ($userModel->register($data) > 0) {
                header('Location: ' . BASEURL . '/user');
                exit;
            }
        }

        $data['title'] = 'Add User';
        $data['user'] = $_SESSION['user'];
        $this->view('layouts/header', $data);
        $this->view('users/add', $data);
        $this->view('layouts/footer', $data);
    }

    public function edit($id) {
        $userModel = $this->model('User');
        $targetUser = $userModel->getUserById($id);

        // Security: Prevent editing admin users
        if (!$targetUser || $targetUser['role'] === 'admin') {
            header('Location: ' . BASEURL . '/user');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $id,
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'role' => 'user' // Keep it as user
            ];

            $userModel->updateUser($data);

            if (!empty($_POST['password'])) {
                $userModel->updatePassword($id, $_POST['password']);
            }

            header('Location: ' . BASEURL . '/user');
            exit;
        }

        $data['title'] = 'Edit User';
        $data['target_user'] = $targetUser;
        $data['user'] = $_SESSION['user'];
        
        $this->view('layouts/header', $data);
        $this->view('users/edit', $data);
        $this->view('layouts/footer', $data);
    }

    public function delete($id) {
        $userModel = $this->model('User');
        $targetUser = $userModel->getUserById($id);

        // Security: Prevent deleting admin users
        if (!$targetUser || $targetUser['role'] === 'admin') {
            header('Location: ' . BASEURL . '/user');
            exit;
        }

        if ($userModel->deleteUser($id) > 0) {
            header('Location: ' . BASEURL . '/user');
            exit;
        }
    }
}
