<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
session_start();

header("Content-Type: application/json");

$pdo = db();
$user_id = $_SESSION['user_id'];

// Fetch Vicidial user
$q = $pdo->prepare("SELECT vicidial_user FROM crm_users WHERE user_id=?");
$q->execute([$user_id]);
$vd_user = $q->fetchColumn();

// latest call
$stmt = $pdo->prepare("
    SELECT * FROM crm_calls
    WHERE agent_user = ?
    ORDER BY start_time DESC LIMIT 1
");
$stmt->execute([$vd_user]);
$call = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => "ok",
    "agent_user" => $vd_user,
    "last_call" => $call
]);
