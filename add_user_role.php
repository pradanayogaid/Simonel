<?php
require_once 'app/core/Database.php';
require_once 'config/config.php';

$db = new Database();

try {
    // Add role column to users table
    $db->query("ALTER TABLE users ADD COLUMN role ENUM('admin', 'user') DEFAULT 'user' AFTER password");
    $db->execute();
    
    // Set existing users to admin (optional, to avoid lockout)
    $db->query("UPDATE users SET role = 'admin'");
    $db->execute();
    
    echo "User roles implemented successfully! Existing users set to 'admin'.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
