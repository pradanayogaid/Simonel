<?php

class Device {
    private $table = 'devices';
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllDevices() {
        $this->db->query('SELECT * FROM ' . $this->table . ' ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function getDeviceById($id) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getDeviceByCode($code) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE device_code = :device_code');
        $this->db->bind(':device_code', $code);
        return $this->db->single();
    }

    public function getDeviceByApiKey($api_key) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE api_key = :api_key');
        $this->db->bind(':api_key', $api_key);
        return $this->db->single();
    }

    public function addDevice($data) {
        $query = "INSERT INTO " . $this->table . " (device_code, device_name, location, status, api_key) 
                  VALUES (:device_code, :device_name, :location, :status, :api_key)";
        $this->db->query($query);
        $this->db->bind(':device_code', $data['device_code']);
        $this->db->bind(':device_name', $data['device_name']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':status', $data['status'] ?? 'OFFLINE');
        $this->db->bind(':api_key', $data['api_key']);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateDevice($data) {
        $query = "UPDATE " . $this->table . " SET device_name = :device_name, location = :location, status = :status WHERE id = :id";
        $this->db->query($query);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':device_name', $data['device_name']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':status', $data['status']);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function deleteDevice($id) {
        $this->db->query('DELETE FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function countDevices() {
        $this->db->query('SELECT COUNT(*) as total FROM ' . $this->table);
        return $this->db->single()['total'];
    }

    public function getStatusCount($status) {
        $this->db->query('SELECT COUNT(*) as total FROM ' . $this->table . ' WHERE status = :status');
        $this->db->bind(':status', $status);
        return $this->db->single()['total'];
    }

    public function getDevicesWithLatestLog() {
        $query = "SELECT d.*, l.voltage, l.current, l.daya_nyata, l.created_at as last_data 
                  FROM " . $this->table . " d 
                  LEFT JOIN (
                      SELECT * FROM sensor_logs 
                      WHERE id IN (SELECT MAX(id) FROM sensor_logs GROUP BY device_id)
                  ) l ON d.device_code = l.device_id 
                  ORDER BY d.status DESC, d.device_name ASC";
        $this->db->query($query);
        return $this->db->resultSet();
    }
    public function generateApiKey() {
        return bin2hex(random_bytes(16));
    }
}
