<?php

class ExportController extends Controller {
    public function __construct() {
    }

    public function index() {
        $deviceModel = $this->model('Device');

        $data['title'] = 'Export';
        $data['user']  = $_SESSION['user'];
        $data['devices'] = $deviceModel->getAllDevices();

        $this->view('layouts/header', $data);
        $this->view('export/index', $data);
        $this->view('layouts/footer', $data);
    }

    public function generate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/export');
            exit;
        }

        if (!verify_csrf_token()) {
            $_SESSION['error'] = 'Sesi form tidak valid. Silakan coba lagi.';
            header('Location: ' . BASEURL . '/export');
            exit;
        }

        $deviceModel = $this->model('Device');
        $logModel    = $this->model('Log');

        $device_id  = $_POST['device_id']  ?? '';
        $date_from  = $_POST['date_from']  ?? '';
        $date_to    = $_POST['date_to']    ?? '';
        $fields     = $_POST['fields']     ?? [];
        $format     = $_POST['format']     ?? 'preview';

        // Validate
        if (empty($device_id) || empty($date_from) || empty($date_to) || empty($fields)) {
            header('Location: ' . BASEURL . '/export?error=missing_fields');
            exit;
        }

        $device = $deviceModel->getDeviceById($device_id);
        if (!$device) {
            header('Location: ' . BASEURL . '/export?error=invalid_device');
            exit;
        }

        $logs = $logModel->getLogsByDateRange($device['device_code'], $date_from, $date_to);

        if ($format === 'csv') {
            // Stream CSV download
            $filename = 'simonel_export_' . $date_from . '_to_' . $date_to . '.csv';
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            $out = fopen('php://output', 'w');

            // Header row
            $headers = ['Waktu'];
            $fieldMap = [
                'voltage'      => 'Tegangan (V AC)',
                'current'      => 'Arus (A)',
                'daya_nyata'   => 'Daya Nyata (W)',
                'daya_semu'    => 'Daya Semu (VA)',
                'daya_reaktif' => 'Daya Reaktif (VAR)',
            ];
            foreach ($fields as $f) {
                if (isset($fieldMap[$f])) $headers[] = $fieldMap[$f];
            }
            fputcsv($out, $headers);

            // Data rows
            foreach ($logs as $row) {
                $line = [date('d/m/Y H:i:s', strtotime($row['created_at']))];
                foreach ($fields as $f) {
                    $line[] = $row[$f] ?? '';
                }
                fputcsv($out, $line);
            }
            fclose($out);
            exit;
        }

        // Preview mode — pass data back to view
        $data['title']     = 'Export';
        $data['user']      = $_SESSION['user'];
        $data['devices']   = $deviceModel->getAllDevices();
        $data['logs']      = $logs;
        $data['fields']    = $fields;
        $data['device']    = $device;
        $data['date_from'] = $date_from;
        $data['date_to']   = $date_to;

        $this->view('layouts/header', $data);
        $this->view('export/index', $data);
        $this->view('layouts/footer', $data);
    }
}
