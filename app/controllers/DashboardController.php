<?php

class DashboardController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $deviceModel = $this->model('Device');
        $logModel = $this->model('Log');

        $data['title'] = 'Dashboard';
        $data['user'] = $_SESSION['user'];
        
        // Summary stats
        $data['stats'] = [
            'total_devices' => $deviceModel->countDevices(),
            'online_devices' => $deviceModel->getStatusCount('ONLINE'),
            'total_power' => $logModel->getTotalPower(),
            'total_energy_today' => $logModel->getEnergyToday()
        ];

        $this->view('layouts/header', $data);
        $this->view('dashboard/index', $data);
        $this->view('layouts/footer', $data);
    }
}
