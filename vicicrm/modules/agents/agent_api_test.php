<?php
require_once "../../config.php";
session_start();

header("Content-Type: application/json");

$pdo = db();

$api_url = rtrim($_POST["api_url"] ?? "", "/") . "/api.php";
$user    = $_POST["user"] ?? "";
$pass    = $_POST["pass"] ?? "";

$params = [
    "source"   => "vicicrm",
    "function" => "agent_logout",  // safest test request
    "user"     => $user,
    "pass"     => $pass
];

$url = $api_url . "?" . http_build_query($params);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo json_encode([
    "status"   => "ok",
    "raw"      => $response,
    "tested"   => $url
]);
