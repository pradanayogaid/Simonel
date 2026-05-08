<?php
require_once 'config/config.php';
require_once 'app/core/Database.php';
require_once 'app/models/Device.php';

$deviceModel = new Device();
$device = $deviceModel->getDeviceByApiKey('wrong_key');

var_dump($device);
