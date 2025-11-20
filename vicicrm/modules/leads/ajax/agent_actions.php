<?php
require "../../../config.php";
require "../../../lib/auth/Auth.php";
require "../../../lib/vicidial/AgentAPI.php";

Auth::start();
if (!Auth::isLoggedIn()) { die("Not logged in"); }

$agent = $_POST['agent'] ?? null;
$action = $_POST['action'] ?? null;

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$api = new VicidialAgentAPI($pdo);

switch ($action) {

    case "hangup":
        $res = $api->send([
            "function" => "external_hangup",
            "agent_user" => $agent
        ]);
        echo "Hangup requested.";
    break;

    case "pause":
        $res = $api->send([
            "function" => "pause_agent",
            "agent_user" => $agent,
            "value" => "PAUSE"
        ]);
        echo "Agent paused.";
    break;

    case "resume":
        $res = $api->send([
            "function" => "agent_status",
            "agent_user" => $agent,
            "value" => "READY"
        ]);
        echo "Agent resumed.";
    break;

    case "dispo":
        $status = $_POST['status'];
        $lead_id = $_POST['lead_id'];

        $res = $api->send([
            "function" => "external_status",
            "agent_user" => $agent,
            "value" => $status,
            "lead_id" => $lead_id
        ]);

        echo "Disposition saved: $status";
    break;

    case "transfer":
        $phone = $_POST['phone'];
        $res = $api->send([
            "function" => "external_transfer",
            "agent_user" => $agent,
            "value" => $phone
        ]);
        echo "Transfer initiated.";
    break;

    case "dtmf":
        $digits = $_POST['digits'];
        $res = $api->send([
            "function" => "external_dtmf",
            "agent_user" => $agent,
            "value" => $digits
        ]);
        echo "DTMF sent.";
    break;

    default:
        echo "Invalid action";
}
