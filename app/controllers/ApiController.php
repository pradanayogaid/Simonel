<?php

class ApiController extends Controller {
    public function send() {
        // 1. Cek Method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            return;
        }

        // 2. Ambil Input
        $input = json_decode(file_get_contents('php://input'), true);
        $api_key = $input['api_key'] ?? $_POST['api_key'] ?? '';

        if (empty($api_key)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'API Key is required']);
            return;
        }

        // 3. Validasi Device
        $deviceModel = $this->model('Device');
        $device = $deviceModel->getDeviceByApiKey($api_key);

        if (!$device) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Invalid API Key']);
            return;
        }

        // 4. Baca Data Sensor (Disamakan dengan Database)
        $voltage = $input['voltage'] ?? $_POST['voltage'] ?? 0;
        $current = $input['current'] ?? $_POST['current'] ?? 0;
        $daya_nyata = $input['daya_nyata'] ?? $_POST['daya_nyata'] ?? 0;
        $daya_semu = $input['daya_semu'] ?? $_POST['daya_semu'] ?? ($voltage * $current);
        $daya_reaktif = $input['daya_reaktif'] ?? $_POST['daya_reaktif'] ?? 0;

        $logModel = $this->model('Log');
        
        $data = [
            'device_id' => $device['device_code'],
            'voltage' => floatval($voltage),
            'current' => floatval($current),
            'daya_semu' => floatval($daya_semu),
            'daya_nyata' => floatval($daya_nyata),
            'daya_reaktif' => floatval($daya_reaktif)
        ];

        // 5. Simpan Data
        if ($logModel->addLog($data) > 0) {
            if ($device['status'] !== 'ONLINE') {
                $device['status'] = 'ONLINE';
                $deviceModel->updateDevice($device);
            }
            echo json_encode(['status' => 'success', 'message' => 'Data recorded successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to record data']);
        }
    }

    public function fetch() {
        $api_key = $_GET['api_key'] ?? '';

        if (empty($api_key)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'API Key is required']);
            return;
        }

        $deviceModel = $this->model('Device');
        $device = $deviceModel->getDeviceByApiKey($api_key);

        if (!$device) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Invalid API Key']);
            return;
        }

        $logModel = $this->model('Log');
        $latestLog = $logModel->getLatestLog($device['device_code']);
        $stats = $logModel->getDailyStats($device['device_code']);

        if (!$latestLog) {
            // No data within 24 hours — return zeroed values
            echo json_encode([
                'status' => 'success',
                'device_code' => $device['device_code'],
                'device_status' => 'OFFLINE',
                'data' => [
                    'voltage'      => 0,
                    'current'      => 0,
                    'daya_nyata'   => 0,
                    'daya_semu'    => 0,
                    'daya_reaktif' => 0,
                    'last_update'  => null
                ],
                'stats' => [
                    'voltage'      => ['avg' => 0, 'min' => 0, 'max' => 0],
                    'current'      => ['avg' => 0, 'min' => 0, 'max' => 0],
                    'daya_nyata'   => ['avg' => 0, 'min' => 0, 'max' => 0],
                    'daya_semu'    => ['avg' => 0, 'min' => 0, 'max' => 0],
                    'daya_reaktif' => ['avg' => 0, 'min' => 0, 'max' => 0],
                ]
            ]);
            return;
        }

        // Output JSON juga disamakan kuncinya dengan database
        echo json_encode([
            'status' => 'success', 
            'device_code' => $device['device_code'],
            'device_status' => $device['status'],
            'data' => [
                'voltage' => $latestLog['voltage'],
                'current' => $latestLog['current'],
                'daya_nyata' => $latestLog['daya_nyata'],
                'daya_semu' => $latestLog['daya_semu'],
                'daya_reaktif' => $latestLog['daya_reaktif'],
                'last_update' => $latestLog['created_at'] ? date('c', strtotime($latestLog['created_at'])) : null
            ],
            'stats' => [
                'voltage' => ['avg' => round($stats['avg_voltage'], 2), 'min' => round($stats['min_voltage'], 2), 'max' => round($stats['max_voltage'], 2)],
                'current' => ['avg' => round($stats['avg_current'], 2), 'min' => round($stats['min_current'], 2), 'max' => round($stats['max_current'], 2)],
                'daya_nyata' => ['avg' => round($stats['avg_daya_nyata'], 2), 'min' => round($stats['min_daya_nyata'], 2), 'max' => round($stats['max_daya_nyata'], 2)],
                'daya_semu' => ['avg' => round($stats['avg_daya_semu'], 2), 'min' => round($stats['min_daya_semu'], 2), 'max' => round($stats['max_daya_semu'], 2)],
                'daya_reaktif' => ['avg' => round($stats['avg_daya_reaktif'], 2), 'min' => round($stats['min_daya_reaktif'], 2), 'max' => round($stats['max_daya_reaktif'], 2)]
            ]
        ]);
    }
    public function history() {
        $api_key = $_GET['api_key'] ?? '';

        if (empty($api_key)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'API Key is required']);
            return;
        }

        $deviceModel = $this->model('Device');
        $device = $deviceModel->getDeviceByApiKey($api_key);

        if (!$device) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Invalid API Key']);
            return;
        }

        $logModel = $this->model('Log');

        // Optional date filter: ?date=YYYY-MM-DD
        $date = $_GET['date'] ?? null;
        if ($date && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $date = null; // ignore invalid format
        }

        $history = $logModel->getChartHistory($device['device_code'], 300, $date);
        $stats   = $logModel->getDailyStats($device['device_code'], $date);

        // Get latest log for the selected period
        $latestLog = $date
            ? $logModel->getLatestLogByDate($device['device_code'], $date)
            : $logModel->getLatestLog($device['device_code']);

        // Format for Chart.js: arrays of labels and values per metric
        $labels      = [];
        $voltage     = [];
        $current     = [];
        $daya_nyata  = [];
        $daya_semu   = [];
        $daya_reaktif = [];

        foreach ($history as $row) {
            $labels[]       = date('c', strtotime($row['created_at']));
            $voltage[]      = (float) $row['voltage'];
            $current[]      = (float) $row['current'];
            $daya_nyata[]   = (float) $row['daya_nyata'];
            $daya_semu[]    = (float) $row['daya_semu'];
            $daya_reaktif[] = (float) $row['daya_reaktif'];
        }

        echo json_encode([
            'status'      => 'success',
            'labels'      => $labels,
            'voltage'     => $voltage,
            'current'     => $current,
            'daya_nyata'  => $daya_nyata,
            'daya_semu'   => $daya_semu,
            'daya_reaktif' => $daya_reaktif,
            // Latest values for the selected period
            'latest' => $latestLog ? [
                'voltage'      => (float) $latestLog['voltage'],
                'current'      => (float) $latestLog['current'],
                'daya_nyata'   => (float) $latestLog['daya_nyata'],
                'daya_semu'    => (float) $latestLog['daya_semu'],
                'daya_reaktif' => (float) $latestLog['daya_reaktif'],
            ] : null,
            // Stats for the selected period
            'stats' => [
                'voltage'     => ['avg' => round($stats['avg_voltage'], 2),     'min' => round($stats['min_voltage'], 2),     'max' => round($stats['max_voltage'], 2)],
                'current'     => ['avg' => round($stats['avg_current'], 2),     'min' => round($stats['min_current'], 2),     'max' => round($stats['max_current'], 2)],
                'daya_nyata'  => ['avg' => round($stats['avg_daya_nyata'], 2),  'min' => round($stats['min_daya_nyata'], 2),  'max' => round($stats['max_daya_nyata'], 2)],
                'daya_semu'   => ['avg' => round($stats['avg_daya_semu'], 2),   'min' => round($stats['min_daya_semu'], 2),   'max' => round($stats['max_daya_semu'], 2)],
                'daya_reaktif' => ['avg' => round($stats['avg_daya_reaktif'], 2), 'min' => round($stats['min_daya_reaktif'], 2), 'max' => round($stats['max_daya_reaktif'], 2)],
            ],
        ]);
    }
}
