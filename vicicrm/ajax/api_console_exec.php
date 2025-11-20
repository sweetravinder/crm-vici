<?php
require_once "../config.php";
require_once "../lib/vicidial/AgentAPI.php";
require_once "../lib/vicidial/NonAgentAPI.php";
require_once "../lib/vicidial/APIMap.php";
require_once "../lib/vicidial/helpers.php";

session_start();
$pdo = db();

if (!isset($_SESSION['user_id'])) {
    ViciHelpers::jsonResponse([
        'status' => 'ERROR',
        'reason' => 'NOT_LOGGED_IN'
    ]);
}

$type     = $_POST['api_type'] ?? null;   // agent | nonagent
$function = $_POST['function'] ?? null;
$params   = $_POST['params'] ?? [];

if (!$type || !$function) {
    ViciHelpers::jsonResponse([
        'status' => 'ERROR',
        'reason'  => 'MISSING_PARAMS'
    ]);
}

$result = [];
$raw = null;
$url = null;

try {

    if ($type == "agent") {
        $API = new AgentAPI($pdo);

        // Build URL manually to show full debug
        $base = rtrim($API->server, "/") . "/agc/api.php";

        $params['function'] = $function;
        $params['source']   = "vicicrm";

        $url = $base . "?" . http_build_query($params);
        $raw = file_get_contents($url);
        $result = json_decode($raw, true) ?: $raw;
    }

    if ($type == "nonagent") {
        $API = new NonAgentAPI($pdo);

        $base = rtrim($API->server, "/") . "/vicidial/non_agent_api.php";
        $params['function'] = $function;
        $params['user']     = $API->user;
        $params['pass']     = $API->pass;
        $params['source']   = "vicicrm";
        $params['format']   = "json";

        $url = $base . "?" . http_build_query($params);
        $raw = file_get_contents($url);
        $result = json_decode($raw, true) ?: $raw;
    }

} catch (Exception $e) {
    ViciHelpers::jsonResponse([
        'status' => 'ERROR',
        'reason' => $e->getMessage()
    ]);
}

ViciHelpers::jsonResponse([
    'status' => 'OK',
    'function' => $function,
    'url' => $url,
    'raw' => $raw,
    'result' => $result
]);
