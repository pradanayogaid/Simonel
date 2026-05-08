<?php

class ApiController extends Controller {

    public function __construct() {
        // Allow CORS for API
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=UTF-8');
        header('Access-Control-Allow-Methods: POST, GET');
        header('Access-Control-Max-Age: 3600');
        header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
    }

    public function index() {
        echo json_encode(['status' => 'error', 'message' => 'Invalid endpoint']);
        exit;
    }

    public function send() {
        // 1. Pastikan Method POST (Kirim Data)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed. Gunakan POST untuk mengirim data.']);
            exit;
        }

        // 2. Prioritaskan pembacaan API Key terlebih dahulu
        $input = json_decode(file_get_contents('php://input'), true);
        $api_key = $input['api_key'] ?? $_POST['api_key'] ?? null;

        if (empty($api_key)) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'API Key is required']);
            exit;
        }

        // 3. Validasi API Key ke Database
        $deviceModel = $this->model('Device');
        $device = $deviceModel->getDeviceByApiKey($api_key);

        if (!$device) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Invalid API Key']);
            exit;
        }

        // 4. Jika valid, barulah baca data sensor lainnya
        $voltage = $input['voltage'] ?? $_POST['voltage'] ?? 0;
        $current = $input['current'] ?? $_POST['current'] ?? 0;
        $power = $input['power'] ?? $_POST['power'] ?? 0;
        $apparent_power = $input['apparent_power'] ?? $_POST['apparent_power'] ?? ($voltage * $current);
        $reactive_power = $input['reactive_power'] ?? $_POST['reactive_power'] ?? null;
        $energy = $input['energy'] ?? $_POST['energy'] ?? 0;
        $frequency = $input['frequency'] ?? $_POST['frequency'] ?? 0;
        $pf = $input['pf'] ?? $_POST['pf'] ?? 0;

        $logModel = $this->model('Log');
        
        $data = [
            'device_id' => $device['device_code'],
            'voltage' => floatval($voltage),
            'current' => floatval($current),
            'power' => floatval($power),
            'apparent_power' => floatval($apparent_power),
            'reactive_power' => $reactive_power !== null ? floatval($reactive_power) : null,
            'energy' => floatval($energy),
            'frequency' => floatval($frequency),
            'pf' => floatval($pf)
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
        // 1. Pastikan Method GET (Ambil Data)
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed. Gunakan GET untuk mengambil data.']);
            exit;
        }

        // 2. Prioritaskan pembacaan API Key
        $api_key = $_GET['api_key'] ?? null;

        if (empty($api_key)) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'API Key is required']);
            exit;
        }

        // 3. Validasi API Key ke Database
        $deviceModel = $this->model('Device');
        $device = $deviceModel->getDeviceByApiKey($api_key);

        if (!$device) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Invalid API Key']);
            exit;
        }

        // 4. Ambil data terakhir dari log
        $logModel = $this->model('Log');
        $latestLog = $logModel->getLatestLog($device['device_code']);
        $stats = $logModel->getDailyStats($device['device_code']);

        if (!$latestLog) {
            echo json_encode([
                'status' => 'success', 
                'device_code' => $device['device_code'],
                'device_status' => $device['status'],
                'data' => null,
                'stats' => null,
                'message' => 'No data recorded yet'
            ]);
            exit;
        }

        echo json_encode([
            'status' => 'success', 
            'device_code' => $device['device_code'],
            'device_status' => $device['status'],
            'data' => [
                'voltage' => $latestLog['voltage'],
                'current' => $latestLog['current'],
                'power' => $latestLog['power'],
                'apparent_power' => $latestLog['apparent_power'],
                'reactive_power' => $latestLog['reactive_power'],
                'energy' => $latestLog['energy'],
                'frequency' => $latestLog['frequency'],
                'pf' => $latestLog['pf'],
                'last_update' => $latestLog['created_at']
            ],
            'stats' => [
                'voltage' => [
                    'avg' => round($stats['avg_voltage'], 2),
                    'min' => round($stats['min_voltage'], 2),
                    'max' => round($stats['max_voltage'], 2)
                ],
                'current' => [
                    'avg' => round($stats['avg_current'], 2),
                    'min' => round($stats['min_current'], 2),
                    'max' => round($stats['max_current'], 2)
                ],
                'power' => [
                    'avg' => round($stats['avg_power'], 2),
                    'min' => round($stats['min_power'], 2),
                    'max' => round($stats['max_power'], 2)
                ],
                'apparent_power' => [
                    'avg' => round($stats['avg_apparent_power'], 2),
                    'min' => round($stats['min_apparent_power'], 2),
                    'max' => round($stats['max_apparent_power'], 2)
                ],
                'reactive_power' => [
                    'avg' => round($stats['avg_reactive_power'], 2),
                    'min' => round($stats['min_reactive_power'], 2),
                    'max' => round($stats['max_reactive_power'], 2)
                ]
            ]
        ]);
    }
}
