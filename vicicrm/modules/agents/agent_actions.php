<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
session_start();

header("Content-Type: application/json");

$pdo = db();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "msg" => "Not logged in"]);
    exit;
}

// Get agent’s Vicidial credentials
$user_id = $_SESSION['user_id'];

$q = $pdo->prepare("SELECT vicidial_user, vicidial_pass FROM crm_users WHERE user_id=?");
$q->execute([$user_id]);
$u = $q->fetch(PDO::FETCH_ASSOC);

if (!$u || !$u['vicidial_user'] || !$u['vicidial_pass']) {
    echo json_encode(["status" => "error", "msg" => "Vicidial credentials missing"]);
    exit;
}

$vd_user = $u['vicidial_user'];
$vd_pass = $u['vicidial_pass'];

// API URL from settings
$s = $pdo->query("SELECT setting_key, setting_value FROM settings_data")
    ->fetchAll(PDO::FETCH_KEY_PAIR);

$api_url = rtrim($s["vicidial_agent_api_url"], "/") . "/api.php";

$action = $_POST['action'] ?? "";
$params = [
    "user"      => $vd_user,
    "pass"      => $vd_pass,
    "source"    => "vicicrm",
];

switch ($action) {

    case "login":
        $params["function"] = "agent_login";
        $params["phone_login"]   = $_POST["phone_login"];
        $params["phone_pass"]    = $_POST["phone_pass"];
        $params["campaign"]      = $_POST["campaign"];
        break;

    case "logout":
        $params["function"] = "agent_logout";
        break;

    case "pause":
        $params["function"] = "agent_pause";
        $params["reason"]   = $_POST["reason"] ?? "";
        break;

    case "resume":
        $params["function"] = "agent_resume";
        break;

    case "hangup":
        $params["function"] = "agent_hangup";
        break;

    case "manual_dial":
        $params["function"] = "agent_dial";
        $params["phone_number"] = $_POST["phone_number"];
        $params["preview"]      = "NO";
        break;

    case "dispo":
        $params["function"] = "agent_dispo";
        $params["dispo_code"] = $_POST["dispo"];
        break;

    case "transfer":
        $params["function"] = "agent_transfer";
        $params["exten"] = $_POST["exten"];
        break;

    default:
        echo json_encode(["status" => "error", "msg" => "Unknown action"]);
        exit;
}

$query = http_build_query($params);

$ch = curl_init("$api_url?$query");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Log locally (optional)
$pdo->prepare("INSERT INTO crm_agent_actions (user_id, action, api_response, created_at) VALUES (?, ?, ?, NOW())")
    ->execute([$user_id, $action, $response]);

echo json_encode([
    "status" => "success",
    "api_raw" => $response
]);
