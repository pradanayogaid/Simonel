<?php
require_once 'app/core/Database.php';
require_once 'config/config.php';

$db = new Database();

try {
    // Step 1: Rename power to temp
    $db->query("ALTER TABLE sensor_logs CHANGE power power_temp FLOAT");
    $db->execute();

    // Step 2: Rename apparent_power to power (Now this becomes Daya Nyata)
    $db->query("ALTER TABLE sensor_logs CHANGE apparent_power power FLOAT");
    $db->execute();

    // Step 3: Rename temp to apparent_power (Now this becomes Daya Semu)
    $db->query("ALTER TABLE sensor_logs CHANGE power_temp apparent_power FLOAT");
    $db->execute();

    // Step 4: Drop other columns
    $db->query("ALTER TABLE sensor_logs DROP COLUMN energy, DROP COLUMN frequency, DROP COLUMN pf");
    $db->execute();

    echo "Database schema updated successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
