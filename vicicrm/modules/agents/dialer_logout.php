<?php
require_once "../../config.php";
session_start();

$pdo = db();

$user_id = $_SESSION['user_id'];

// Fetch credentials
$stmt = $pdo->prepare("SELECT vicidial_user, vicidial_pass FROM crm_users WHERE user_id=?");
$stmt->execute([$user_id]);
$agent = $stmt->fetch(PDO::FETCH_ASSOC);

$s = $pdo->query("SELECT setting_key, setting_value FROM settings_data")
    ->fetchAll(PDO::FETCH_KEY_PAIR);

$api_url = rtrim($s["vicidial_agent_api_url"], "/") . "/api.php";

$params = [
    "source"   => "vicicrm",
    "function" => "agent_logout",
    "user"     => $agent["vicidial_user"],
    "pass"     => $agent["vicidial_pass"],
];

$url = $api_url . "?" . http_build_query($params);

// Call
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Remove CRM session
$pdo->prepare("DELETE FROM crm_agent_sessions WHERE user_id=?")->execute([$user_id]);

echo "<script>alert('Logged out: $response'); window.location='dialer_login.php';</script>";
