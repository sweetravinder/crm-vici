<?php
require "../../../config.php";

$lead_id = $_GET['lead_id'];

$out = $pdo->prepare("SELECT * FROM vicidial_log WHERE lead_id=? ORDER BY call_date DESC");
$out->execute([$lead_id]);

$in = $pdo->prepare("SELECT * FROM vicidial_closer_log WHERE lead_id=? ORDER BY call_date DESC");
$in->execute([$lead_id]);

echo "<b>Outbound Calls</b><br>";

foreach ($out as $row) {
    echo "<div style='padding:6px;border-bottom:1px solid #ddd;'>
            <b>{$row['status']}</b> — {$row['phone_number']}<br>
            {$row['call_date']} — Duration: {$row['length_in_sec']} sec
          </div>";
}

echo "<br><b>Inbound Calls</b><br>";

foreach ($in as $row) {
    echo "<div style='padding:6px;border-bottom:1px solid #ddd;'>
            <b>{$row['status']}</b> — {$row['phone_number']}<br>
            {$row['call_date']} — Duration: {$row['length_in_sec']} sec
          </div>";
}
