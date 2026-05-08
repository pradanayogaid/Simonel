<?php
require_once 'app/core/Database.php';
require_once 'config/config.php';

$db = new Database();

try {
    // Rename columns to explicit names
    $db->query("ALTER TABLE sensor_logs 
                CHANGE power daya_semu FLOAT,
                CHANGE apparent_power daya_nyata FLOAT,
                CHANGE reactive_power daya_reaktif FLOAT");
    $db->execute();
    echo "Database columns renamed successfully to daya_semu, daya_nyata, and daya_reaktif.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
