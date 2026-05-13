<?php

class RealtimeController extends Controller {
    public function __construct() {
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
            // Find 'microinverter a' in the list
            $default_device = null;
            foreach ($data['devices'] as $device) {
                if (strtolower($device['device_name']) === 'microinverter a' || strtolower($device['device_code']) === 'microinverter a') {
                    $default_device = $device;
                    break;
                }
            }
            
            // If found, use it, otherwise use the first one
            $data['selected_device'] = $default_device ? $default_device : $data['devices'][0];
        } else {
            $data['selected_device'] = null;
        }

        $this->view('layouts/header', $data);
        $this->view('realtime/index', $data);
        $this->view('layouts/footer', $data);
    }
}
