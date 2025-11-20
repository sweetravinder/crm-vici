<?php
require "../../../config.php";

$lead_id = $_GET['lead_id'];

$q = $pdo->prepare("SELECT * FROM recording_log WHERE lead_id=? ORDER BY start_time DESC");
$q->execute([$lead_id]);

foreach ($q as $rec) {
    $file = $rec['location'];

    echo "<div style='padding:6px;border-bottom:1px solid #ddd;'>
            <b>{$rec['start_time']}</b><br>
            <audio controls src='$file' style='width:100%;'></audio><br>
            <a href='$file' download>Download</a>
          </div>";
}
