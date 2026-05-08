<?php

class RealtimeController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $deviceModel = $this->model('Device');
        
        $data['title'] = 'Realtime';
        $data['user'] = $_SESSION['user'];
        $data['devices'] = $deviceModel->getAllDevices();

        // If a device_id is provided in URL, select it, otherwise default to first device
        $selected_id = isset($_GET['device']) ? $_GET['device'] : null;
        
        if ($selected_id) {
            $data['selected_device'] = $deviceModel->getDeviceById($selected_id);
        } else if (!empty($data['devices'])) {
            $data['selected_device'] = $data['devices'][0];
        } else {
            $data['selected_device'] = null;
        }

        $this->view('layouts/header', $data);
        $this->view('realtime/index', $data);
        $this->view('layouts/footer', $data);
    }
}
