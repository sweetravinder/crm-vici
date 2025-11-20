<?php
require "../../../config.php";

// Required POST
$action = $_POST['action'] ?? "";
$agent  = $_POST['agent'] ?? "";

if (!$action || !$agent) die("Invalid");

// Get agent channel
$q = $pdo->prepare("
SELECT channel 
FROM vicidial_live_agents 
WHERE user=? LIMIT 1
");
$q->execute([$agent]);
$row = $q->fetch(PDO::FETCH_ASSOC);

if (!$row) die("Agent not on call");

$channel = $row['channel'];

// AMI request
$socket = fsockopen($ami_host, 5038, $errno, $errstr, 5);
if (!$socket) die("AMI connection failed");

fputs($socket, "Action: Login\r\nUsername: $ami_user\r\nSecret: $ami_pass\r\n\r\n");

if ($action == "listen") {
    $spy = "ChanSpy/$channel,q";
} elseif ($action == "whisper") {
    $spy = "ChanSpy/$channel,w";
} elseif ($action == "barge") {
    $spy = "ChanSpy/$channel,B";
} else {
    die("Invalid action");
}

fputs($socket, "Action: Originate\r\nChannel: Local/$agent@default\r\nApplication: $spy\r\n\r\n");

fclose($socket);

echo ucfirst($action)." started for $agent";
