<?php
require "../../../config.php";

$lead_id = $_GET['lead_id'];

$q = $pdo->prepare("SELECT * FROM crm_notes WHERE lead_id=? ORDER BY created_at DESC");
$q->execute([$lead_id]);

foreach ($q as $n) {
    echo "<div style='padding:6px;border-bottom:1px solid #ddd;'>
            <b>{$n['agent_user']}</b> — {$n['created_at']}<br>
            ".nl2br(htmlspecialchars($n['note']))."
          </div>";
}
