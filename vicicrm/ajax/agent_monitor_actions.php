<?php
require_once "../config.php";
require_once "../lib/vicidial/NonAgentAPI.php";

session_start();
$pdo = db();

// Only admin/supervisor
if (!in_array($_SESSION['role_id'], [1, 2])) {
    die(json_encode(["status" => "ERROR", "reason" => "NO_PERMISSION"]));
}

$API = new NonAgentAPI($pdo);

$action = $_POST['action'] ?? null;
$user   = $_POST['agent']  ?? null;

if (!$action || !$user) {
    die(json_encode(["status" => "ERROR", "reason" => "MISSING_DATA"]));
}

switch ($action) {

    case "monitor":
        $res = $API->call("manager_monitor", [
            "user" => $user,
            "type" => "MONITOR"
        ]);
    break;

    case "whisper":
        $res = $API->call("manager_monitor", [
            "user" => $user,
            "type" => "WHISPER"
        ]);
    break;

    case "barge":
        $res = $API->call("manager_monitor", [
            "user" => $user,
            "type" => "BARGE"
        ]);
    break;

    case "pause":
        $res = $API->call("pause_agent", [
            "user" => $user,
            "pause_code" => "FORCED"
        ]);
    break;

    case "logout":
        $res = $API->call("logout", [
            "user" => $user
        ]);
    break;

    default:
        $res = ["status" => "INVALID_ACTION"];
}

echo json_encode($res);
