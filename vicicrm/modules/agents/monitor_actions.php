<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
session_start();

header("Content-Type: application/json");

$pdo = db();

if (!can_view("monitor_actions", $pdo)) {
    echo json_encode(["error" => "ACCESS_DENIED"]);
    exit;
}

$action = $_POST['action'];
$target = $_POST['agent_user'];

$s = $pdo->query("SELECT setting_key, setting_value FROM settings_data")
    ->fetchAll(PDO::FETCH_KEY_PAIR);

$api_url = rtrim($s["vicidial_non_agent_api_url"], "/") . "/non_agent_api.php";

$params = [
    "user"     => $s["vicidial_admin_user"],
    "pass"     => $s["vicidial_admin_pass"],
    "source"   => "vicicrm",
    "agent_user" => $target
];

switch ($action) {
    case "listen":   $params["function"] = "monitor"; break;
    case "whisper":  $params["function"] = "whisper"; break;
    case "barge":    $params["function"] = "barge"; break;
    case "hangup":   $params["function"] = "hangup_agent"; break;
    case "pause":    $params["function"] = "external_pause"; break;
    case "resume":   $params["function"] = "external_resume"; break;
    default:
        echo json_encode(["error" => "Invalid action"]);
        exit;
}

$url = $api_url . "?" . http_build_query($params);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo json_encode([
    "status" => "ok",
    "api_response" => $response
]);
