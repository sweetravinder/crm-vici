<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
session_start();

$pdo = db();

// PERMISSION CHECK
if (!can_delete("lead_delete", $pdo)) {
    die("ACCESS DENIED");
}

$lead_id = $_GET['lead_id'] ?? 0;
if (!$lead_id) die("Invalid lead");

$stmt = $pdo->prepare("DELETE FROM crm_leads WHERE lead_id=?");
$stmt->execute([$lead_id]);

header("Location: leads_list.php?msg=deleted");
exit;
