<?php
require_once 'app/core/Database.php';
require_once 'config/config.php';

$db = new Database();
$db->query("DESCRIBE sensor_logs");
$result = $db->resultSet();

echo "Table: sensor_logs\n";
echo str_pad("Field", 20) . " | " . str_pad("Type", 20) . "\n";
echo str_repeat("-", 45) . "\n";
foreach ($result as $row) {
    echo str_pad($row['Field'], 20) . " | " . str_pad($row['Type'], 20) . "\n";
}
