<?php
require_once 'config/config.php';
require_once 'app/core/Database.php';

$db = new Database();
$db->query("ALTER TABLE sensor_logs ADD COLUMN apparent_power float DEFAULT 0 AFTER power");
try {
    $db->execute();
    echo "Column apparent_power added successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
