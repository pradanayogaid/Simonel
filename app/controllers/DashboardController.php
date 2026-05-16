<?php

class DashboardController extends Controller {
    public function __construct() {
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
            'total_power_apparent' => $logModel->getTotalApparentPower(),
            'system_efficiency' => $logModel->getSystemEfficiency(),
            'daily_peak_power' => $logModel->getDailyPeakPower()
        ];

        // New data for enhanced dashboard
        $data['devices'] = $deviceModel->getDevicesWithLatestLog();
        $data['weekly_consumption'] = $logModel->getWeeklyConsumption();

        $this->view('layouts/header', $data);
        $this->view('dashboard/index', $data);
        $this->view('layouts/footer', $data);
    }
}
