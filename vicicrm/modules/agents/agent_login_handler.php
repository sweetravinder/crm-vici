<?php
require_once "../../config.php";
session_start();

header("Content-Type: application/json");

$pdo = db();

$user_id = $_SESSION['user_id'];

// Get agent cred
$stmt = $pdo->prepare("SELECT vicidial_user, vicidial_pass FROM crm_users WHERE user_id=?");
$stmt->execute([$user_id]);
$agent = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$agent) {
    echo json_encode(["status"=>"error", "msg"=>"Invalid agent"]);
    exit;
}

$vd_user = $agent["vicidial_user"];
$vd_pass = $agent["vicidial_pass"];

$s = $pdo->query("SELECT setting_key, setting_value FROM settings_data")
    ->fetchAll(PDO::FETCH_KEY_PAIR);

$api_url = rtrim($s["vicidial_agent_api_url"], "/") . "/api.php";

// Build URL
$params = [
    "source"      => "vicicrm",
    "function"    => "agent_login",
    "user"        => $vd_user,
    "pass"        => $vd_pass,
    "phone_login" => $_POST["phone_login"],
    "phone_pass"  => $_POST["phone_pass"],
    "campaign"    => $_POST["campaign"],
];

$url = $api_url . "?" . http_build_query($params);

// API Request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Save session locally
$pdo->prepare("
    INSERT INTO crm_agent_sessions (user_id, campaign_id, phone_login, created_at) 
    VALUES (?, ?, ?, NOW())
")->execute([$user_id, $_POST["campaign"], $_POST["phone_login"]]);

echo json_encode([
    "status" => "success",
    "msg"    => "Login response: " . $response
]);
