<?php
require "../../../config.php";

$user = $_GET['user'] ?? "";

if (!$user) {
    die("Invalid");
}

// Try VICIDIAL LIVE AGENTS
$q = $pdo->prepare("
SELECT status, campaign_id 
FROM vicidial_live_agents 
WHERE user=?
LIMIT 1
");
$q->execute([$user]);

if ($q->rowCount()) {
    $row = $q->fetch(PDO::FETCH_ASSOC);
    echo "<b>{$row['status']}</b> ({$row['campaign_id']})";
    exit;
}

// If not in live agents → offline
echo "<span style='color:red;'>OFFLINE</span>";
