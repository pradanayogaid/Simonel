<?php
date_default_timezone_set('Asia/Jakarta');
require_once '../config/config.php';

// --- Session Security Logic ---
if (!session_id()) session_start();

// 1. Auto-Logout Logic (30 Minutes Timeout)
$timeout_duration = 1800; // 30 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout_duration)) {
    session_unset();
    session_destroy();
    header('Location: ' . BASEURL . '/auth?error=session_expired');
    exit;
}
$_SESSION['last_activity'] = time();

// 2. Global Login Enforcement
$url = isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : [];
$currentController = isset($url[0]) ? ucfirst($url[0]) . 'Controller' : 'DashboardController';

// Exclude Auth and Api from mandatory login
$excludedControllers = ['AuthController', 'ApiController'];
if (!in_array($currentController, $excludedControllers)) {
    if (!isset($_SESSION['user'])) {
        header('Location: ' . BASEURL . '/auth');
        exit;
    }
}
// -----------------------------

require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Database.php';
