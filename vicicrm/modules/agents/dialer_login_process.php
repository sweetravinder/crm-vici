<?php
require_once "../../config.php";
require_once "../../lib/vicidial/AgentAPI.php";
require_once "../../lib/vicidial/helpers.php";
session_start();

$pdo = db();

$user = $_POST['user'] ?? '';
$phone_login = $_POST['phone_login'] ?? '';
$phone_pass = $_POST['phone_pass'] ?? '';
$campaign = $_POST['campaign'] ?? '';

if (!$user || !$phone_login || !$phone_pass || !$campaign) {
    die("Missing required fields.");
}

$API = new AgentAPI($pdo);

// API login
$res = $API->call("login", [
    "user"        => $user,
    "pass"        => "",
    "phone_login" => $phone_login,
    "phone_pass"  => $phone_pass,
    "campaign"    => $campaign
]);

if (isset($res['result']) && $res['result'] == "SUCCESS") {

    $_SESSION['vicidial_user'] = $user;
    $_SESSION['vicidial_session_id'] = $res['session_id'] ?? null;
    $_SESSION['vicidial_campaign'] = $campaign;

    header("Location: ../../dashboard_agent.php");
    exit;
}

echo "<h3>Dialer Login Failed</h3>";
echo "<pre>";
print_r($res);
echo "</pre>";
exit;
