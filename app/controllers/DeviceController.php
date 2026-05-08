<?php

class DeviceController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $deviceModel = $this->model('Device');
        $data['title'] = 'Devices';
        $data['devices'] = $deviceModel->getAllDevices();
        $data['user'] = $_SESSION['user'];

        $this->view('layouts/header', $data);
        $this->view('devices/index', $data);
        $this->view('layouts/footer', $data);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $deviceModel = $this->model('Device');
            
            $data = [
                'device_code' => $_POST['device_code'],
                'device_name' => $_POST['device_name'],
                'location' => $_POST['location'],
                'status' => 'OFFLINE',
                'api_key' => $deviceModel->generateApiKey()
            ];

            if ($deviceModel->addDevice($data) > 0) {
                header('Location: ' . BASEURL . '/device');
                exit;
            }
        }

        $data['title'] = 'Add Device';
        $data['user'] = $_SESSION['user'];
        $this->view('layouts/header', $data);
        $this->view('devices/add', $data);
        $this->view('layouts/footer', $data);
    }

    public function edit($id) {
        $deviceModel = $this->model('Device');
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $id,
                'device_name' => $_POST['device_name'],
                'location' => $_POST['location'],
                'status' => $_POST['status']
            ];

            if ($deviceModel->updateDevice($data) >= 0) {
                header('Location: ' . BASEURL . '/device');
                exit;
            }
        }

        $data['title'] = 'Edit Device';
        $data['device'] = $deviceModel->getDeviceById($id);
        $data['user'] = $_SESSION['user'];
        
        $this->view('layouts/header', $data);
        $this->view('devices/edit', $data);
        $this->view('layouts/footer', $data);
    }

    public function delete($id) {
        $deviceModel = $this->model('Device');
        if ($deviceModel->deleteDevice($id) > 0) {
            header('Location: ' . BASEURL . '/device');
            exit;
        }
    }
}
