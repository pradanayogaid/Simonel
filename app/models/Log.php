<?php

class Log {
    private $table = 'sensor_logs';
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addLog($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (device_id, voltage, current, power, apparent_power, reactive_power, energy, frequency, pf) 
                  VALUES 
                  (:device_id, :voltage, :current, :power, :apparent_power, :reactive_power, :energy, :frequency, :pf)";
                  
        $this->db->query($query);
        $this->db->bind(':device_id', $data['device_id']);
        $this->db->bind(':voltage', $data['voltage']);
        $this->db->bind(':current', $data['current']);
        $this->db->bind(':power', $data['power']);
        
        $apparent = $data['apparent_power'] ?? ($data['voltage'] * $data['current']);
        $this->db->bind(':apparent_power', $apparent); 
        
        // Calculate reactive power if not provided (VAR = sqrt(VA^2 - W^2))
        $reactive = $data['reactive_power'] ?? 0;
        if (!isset($data['reactive_power']) && $apparent >= $data['power']) {
            $reactive = sqrt(pow($apparent, 2) - pow($data['power'], 2));
        }
        $this->db->bind(':reactive_power', $reactive);
        
        $this->db->bind(':energy', $data['energy']);
        $this->db->bind(':frequency', $data['frequency']);
        $this->db->bind(':pf', $data['pf']);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getLatestLog($device_code) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE device_id = :device_id ORDER BY created_at DESC LIMIT 1');
        $this->db->bind(':device_id', $device_code);
        return $this->db->single();
    }

    public function getDailyStats($device_code) {
        // We get average, min, max for today
        $query = "SELECT 
                    AVG(voltage) as avg_voltage, MIN(voltage) as min_voltage, MAX(voltage) as max_voltage,
                    AVG(current) as avg_current, MIN(current) as min_current, MAX(current) as max_current,
                    AVG(power) as avg_power, MIN(power) as min_power, MAX(power) as max_power,
                    AVG(apparent_power) as avg_apparent_power, MIN(apparent_power) as min_apparent_power, MAX(apparent_power) as max_apparent_power,
                    AVG(reactive_power) as avg_reactive_power, MIN(reactive_power) as min_reactive_power, MAX(reactive_power) as max_reactive_power
                  FROM " . $this->table . " 
                  WHERE device_id = :device_id AND DATE(created_at) = CURDATE()";
        
        $this->db->query($query);
        $this->db->bind(':device_id', $device_code);
        $stats = $this->db->single();
        
        // Return 0 if no data today
        return $stats ?: [
            'avg_voltage' => 0, 'min_voltage' => 0, 'max_voltage' => 0,
            'avg_current' => 0, 'min_current' => 0, 'max_current' => 0,
            'avg_power' => 0, 'min_power' => 0, 'max_power' => 0,
            'avg_apparent_power' => 0, 'min_apparent_power' => 0, 'max_apparent_power' => 0,
            'avg_reactive_power' => 0, 'min_reactive_power' => 0, 'max_reactive_power' => 0
        ];
    }

    public function getTotalPower() {
        // Sum of latest power from each unique device
        $query = "SELECT SUM(power) as total_power 
                  FROM (SELECT power FROM " . $this->table . " 
                        WHERE id IN (SELECT MAX(id) FROM " . $this->table . " GROUP BY device_id)) as latest_logs";
        $this->db->query($query);
        return $this->db->single()['total_power'] ?? 0;
    }

    public function getEnergyToday() {
        // Sum of energy consumption for today
        // Since energy is usually cumulative, we take MAX - MIN for each device today
        $query = "SELECT SUM(energy_today) as total_energy 
                  FROM (SELECT (MAX(energy) - MIN(energy)) as energy_today 
                        FROM " . $this->table . " 
                        WHERE DATE(created_at) = CURDATE() 
                        GROUP BY device_id) as daily_energy";
        $this->db->query($query);
        return $this->db->single()['total_energy'] ?? 0;
    }

    public function getAllLogs($limit = 100) {
        $this->db->query('SELECT ' . $this->table . '.*, devices.device_name 
                          FROM ' . $this->table . ' 
                          JOIN devices ON ' . $this->table . '.device_id = devices.device_code 
                          ORDER BY created_at DESC LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
}
