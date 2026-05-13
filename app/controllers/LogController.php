<?php

class LogController extends Controller {
    public function __construct() {
    }

    public function index() {
        $logModel = $this->model('Log');
        $search = $_GET['search'] ?? null;
        
        $data['title'] = 'Logs';
        $data['user'] = $_SESSION['user'];
        // Fetch more records to allow pagination (e.g., last 1000 or all if possible)
        $data['logs'] = $logModel->getAllLogs(null, $search); 
        $data['search'] = $search;

        $this->view('layouts/header', $data);
        $this->view('logs/index', $data);
        $this->view('layouts/footer', $data);
    }
}
