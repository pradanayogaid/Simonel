<?php

class User {
    private $table = 'users';
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getUserByEmail($email) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function getUserById($id) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function register($data) {
        $query = "INSERT INTO " . $this->table . " (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $this->db->query($query);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':role', $data['role'] ?? 'user');
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getAllUsers() {
        $this->db->query('SELECT * FROM ' . $this->table . ' ORDER BY name ASC');
        return $this->db->resultSet();
    }

    public function updateUser($data) {
        $query = "UPDATE " . $this->table . " SET name = :name, email = :email, role = :role WHERE id = :id";
        $this->db->query($query);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':role', $data['role']);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function deleteUser($id) {
        $this->db->query('DELETE FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateProfile($id, $name, $email) {
        $query = "UPDATE " . $this->table . " SET name = :name, email = :email WHERE id = :id";
        $this->db->query($query);
        $this->db->bind(':id', $id);
        $this->db->bind(':name', $name);
        $this->db->bind(':email', $email);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updatePassword($id, $password) {
        $query = "UPDATE " . $this->table . " SET password = :password WHERE id = :id";
        $this->db->query($query);
        $this->db->bind(':id', $id);
        $this->db->bind(':password', password_hash($password, PASSWORD_DEFAULT));
        
        $this->db->execute();
        return $this->db->rowCount();
    }
}
