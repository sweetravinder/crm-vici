<?php
require_once "../config.php";
require_once "../lib/vicidial/AgentAPI.php";
require_once "../lib/vicidial/helpers.php";

session_start();
$pdo = db();

if (!isset($_SESSION['vicidial_user'])) {
    ViciHelpers::jsonResponse([
        'status' => 'ERROR',
        'reason' => 'NOT_LOGGED_IN'
    ]);
}

$function = $_POST['function'] ?? null;
if (!$function) {
    ViciHelpers::jsonResponse([
        'status' => 'ERROR',
        'reason' => 'NO_FUNCTION'
    ]);
}

$agent_user = $_SESSION['vicidial_user'];

$API = new AgentAPI($pdo);

// Build the full parameter set dynamically
$params = $_POST;
$params['user'] = $agent_user;

// Remove function key from param duplication
unset($params['function']);

// Call VICIdial Agent API
$result = $API->call($function, $params);

// Respond back to JS
ViciHelpers::jsonResponse([
    'status'  => 'OK',
    'api'     => $function,
    'result'  => $result
]);

