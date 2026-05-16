<?php
require_once 'config/config.php';
require_once 'app/core/Database.php';

$db = new Database();

function columnExists($db, $column) {
    $db->query("SHOW COLUMNS FROM sensor_logs LIKE :column");
    $db->bind(':column', $column);
    return (bool) $db->single();
}

function runAlter($db, $sql) {
    $db->query($sql);
    $db->execute();
}

try {
    if (columnExists($db, 'power') && !columnExists($db, 'daya_semu')) {
        runAlter($db, "ALTER TABLE sensor_logs CHANGE power daya_semu FLOAT DEFAULT 0");
    }

    if (columnExists($db, 'apparent_power') && !columnExists($db, 'daya_nyata')) {
        runAlter($db, "ALTER TABLE sensor_logs CHANGE apparent_power daya_nyata FLOAT DEFAULT 0");
    }

    if (columnExists($db, 'reactive_power') && !columnExists($db, 'daya_reaktif')) {
        runAlter($db, "ALTER TABLE sensor_logs CHANGE reactive_power daya_reaktif FLOAT DEFAULT 0");
    }

    foreach (['energy', 'frequency', 'pf'] as $column) {
        if (columnExists($db, $column)) {
            runAlter($db, "ALTER TABLE sensor_logs DROP COLUMN {$column}");
        }
    }

    echo "Database schema is aligned with SIMONEL daya_* columns.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
