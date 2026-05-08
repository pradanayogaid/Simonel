<?php

class LogController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $logModel = $this->model('Log');
        $search = $_GET['search'] ?? null;
        
        $data['title'] = 'Logs';
        $data['user'] = $_SESSION['user'];
        $data['logs'] = $logModel->getAllLogs(100, $search); // Show last 100 logs
        $data['search'] = $search;

        $this->view('layouts/header', $data);
        $this->view('logs/index', $data);
        $this->view('layouts/footer', $data);
    }
}
