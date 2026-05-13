<?php

class Log {
    private $table = 'sensor_logs';
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addLog($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (device_id, voltage, current, daya_semu, daya_nyata, daya_reaktif) 
                  VALUES 
                  (:device_id, :voltage, :current, :daya_semu, :daya_nyata, :daya_reaktif)";
                  
        $this->db->query($query);
        $this->db->bind(':device_id', $data['device_id']);
        $this->db->bind(':voltage', $data['voltage']);
        $this->db->bind(':current', $data['current']);
        $this->db->bind(':daya_semu', $data['daya_semu']);
        $this->db->bind(':daya_nyata', $data['daya_nyata']); 
        $this->db->bind(':daya_reaktif', $data['daya_reaktif'] ?? 0);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getLatestLog($device_code) {
        // Only return latest data if it's within the last 24 hours
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE device_id = :device_id AND created_at >= NOW() - INTERVAL 24 HOUR ORDER BY created_at DESC LIMIT 1');
        $this->db->bind(':device_id', $device_code);
        return $this->db->single();
    }

    public function getLatestLogByDate($device_code, $date) {
        // Return the most recent log for a specific date
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE device_id = :device_id AND DATE(created_at) = :date ORDER BY created_at DESC LIMIT 1');
        $this->db->bind(':device_id', $device_code);
        $this->db->bind(':date', $date);
        return $this->db->single();
    }

    public function getDailyStats($device_code, $date = null) {
        // Get average, min, max — filtered by specific date or last 24 hours
        $dateFilter = $date 
            ? "DATE(created_at) = :date" 
            : "created_at >= NOW() - INTERVAL 24 HOUR";

        $query = "SELECT 
                    AVG(voltage) as avg_voltage, MIN(voltage) as min_voltage, MAX(voltage) as max_voltage,
                    AVG(current) as avg_current, MIN(current) as min_current, MAX(current) as max_current,
                    AVG(daya_semu) as avg_daya_semu, MIN(daya_semu) as min_daya_semu, MAX(daya_semu) as max_daya_semu,
                    AVG(daya_nyata) as avg_daya_nyata, MIN(daya_nyata) as min_daya_nyata, MAX(daya_nyata) as max_daya_nyata,
                    AVG(daya_reaktif) as avg_daya_reaktif, MIN(daya_reaktif) as min_daya_reaktif, MAX(daya_reaktif) as max_daya_reaktif
                  FROM " . $this->table . " 
                  WHERE device_id = :device_id AND " . $dateFilter;
        
        $this->db->query($query);
        $this->db->bind(':device_id', $device_code);
        if ($date) $this->db->bind(':date', $date);
        $stats = $this->db->single();
        
        // Return 0 if no data
        return $stats ?: [
            'avg_voltage' => 0, 'min_voltage' => 0, 'max_voltage' => 0,
            'avg_current' => 0, 'min_current' => 0, 'max_current' => 0,
            'avg_daya_semu' => 0, 'min_daya_semu' => 0, 'max_daya_semu' => 0,
            'avg_daya_nyata' => 0, 'min_daya_nyata' => 0, 'max_daya_nyata' => 0,
            'avg_daya_reaktif' => 0, 'min_daya_reaktif' => 0, 'max_daya_reaktif' => 0
        ];
    }

    public function getChartHistory($device_code, $limit = 60, $date = null) {
        // If date given, fetch all records for that day; otherwise last 24h
        if ($date) {
            $query = "SELECT voltage, current, daya_nyata, daya_semu, daya_reaktif, created_at
                      FROM " . $this->table . "
                      WHERE device_id = :device_id AND DATE(created_at) = :date
                      ORDER BY created_at ASC
                      LIMIT :limit";
            $this->db->query($query);
            $this->db->bind(':device_id', $device_code);
            $this->db->bind(':date', $date);
            $this->db->bind(':limit', $limit);
        } else {
            // Default: last 24 hours, newest-first limited, then ordered ascending
            $query = "SELECT voltage, current, daya_nyata, daya_semu, daya_reaktif, created_at
                      FROM (
                          SELECT voltage, current, daya_nyata, daya_semu, daya_reaktif, created_at
                          FROM " . $this->table . "
                          WHERE device_id = :device_id AND created_at >= NOW() - INTERVAL 24 HOUR
                          ORDER BY created_at DESC
                          LIMIT :limit
                      ) sub
                      ORDER BY created_at ASC";
            $this->db->query($query);
            $this->db->bind(':device_id', $device_code);
            $this->db->bind(':limit', $limit);
        }
        return $this->db->resultSet();
    }

    public function getTotalPower() {
        // Sum of latest Daya Nyata (W)
        $query = "SELECT SUM(daya_nyata) as total_power 
                  FROM (SELECT daya_nyata FROM " . $this->table . " 
                        WHERE id IN (SELECT MAX(id) FROM " . $this->table . " GROUP BY device_id)) as latest_logs";
        $this->db->query($query);
        return $this->db->single()['total_power'] ?? 0;
    }

    public function getTotalApparentPower() {
        // Sum of latest Daya Semu (VA)
        $query = "SELECT SUM(daya_semu) as total_power 
                  FROM (SELECT daya_semu FROM " . $this->table . " 
                        WHERE id IN (SELECT MAX(id) FROM " . $this->table . " GROUP BY device_id)) as latest_logs";
        $this->db->query($query);
        return $this->db->single()['total_power'] ?? 0;
    }

    public function getAllLogs($limit = null, $search = null) {
        $sql = 'SELECT ' . $this->table . '.*, devices.device_name 
                FROM ' . $this->table . ' 
                JOIN devices ON ' . $this->table . '.device_id = devices.device_code';
        
        if ($search) {
            $sql .= ' WHERE devices.device_name LIKE :search 
                      OR ' . $this->table . '.device_id LIKE :search';
        }
        
        $sql .= ' ORDER BY created_at DESC';
        
        if ($limit) {
            $sql .= ' LIMIT :limit';
        }
        
        $this->db->query($sql);
        if ($search) {
            $this->db->bind(':search', "%$search%");
        }
        if ($limit) {
            $this->db->bind(':limit', $limit);
        }
        return $this->db->resultSet();
    }

    public function getWeeklyConsumption() {
        // Get daily average of Daya Nyata (W) for the last 7 days
        $query = "SELECT date, SUM(daily_avg_power) as total_energy 
                  FROM (
                      SELECT DATE(created_at) as date, AVG(daya_nyata) as daily_avg_power 
                      FROM " . $this->table . " 
                      WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) 
                      GROUP BY DATE(created_at), device_id
                  ) as daily_stats 
                  GROUP BY date 
                  ORDER BY date ASC";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getLogsByDateRange($device_code, $date_from, $date_to) {
        $query = "SELECT voltage, current, daya_nyata, daya_semu, daya_reaktif, created_at
                  FROM " . $this->table . "
                  WHERE device_id = :device_id
                    AND DATE(created_at) >= :date_from
                    AND DATE(created_at) <= :date_to
                  ORDER BY created_at ASC";
        $this->db->query($query);
        $this->db->bind(':device_id', $device_code);
        $this->db->bind(':date_from', $date_from);
        $this->db->bind(':date_to', $date_to);
        return $this->db->resultSet();
    }
}
