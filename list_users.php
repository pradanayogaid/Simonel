<?php
require_once 'app/core/Database.php';
require_once 'config/config.php';

$db = new Database();
$db->query("SELECT name, role FROM users");
$users = $db->resultSet();

foreach ($users as $user) {
    echo "Name: " . $user['name'] . " | Role: " . ($user['role'] ?? 'NULL') . "\n";
}
