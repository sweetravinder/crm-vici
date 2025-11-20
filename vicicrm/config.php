<?php
// Enterprise Config Loader

session_start();
date_default_timezone_set('Asia/Kolkata');

// CRM DB
function db() {
    static $pdo;
    if (!$pdo) {
        $pdo = new PDO("mysql:host=localhost;dbname=ravicrm;charset=utf8mb4","root","",[
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
        ]);
    }
    return $pdo;
}

// Load Settings
require_once __DIR__."/lib/utils/Settings.php";
$settings = Settings::all();

// Vicidial DB
function dbVici() {
    global $settings;
    static $vdb;
    if (!$vdb) {
        $vdb = new PDO(
            "mysql:host={$settings['vici_db_host']};dbname={$settings['vici_db_name']};charset=utf8mb4",
            $settings['vici_db_user'],
            $settings['vici_db_pass'],
            [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]
        );
    }
    return $vdb;
}
?>