<?php
require "../config.php";

$from = date("Y-m-d 00:00:00");
$to   = date("Y-m-d 23:59:59");

$q = $pdo->prepare("
SELECT recording_id, lead_id, user, campaign_id, location,
       length_in_sec, start_time, caller_code AS phone
FROM recording_log
WHERE start_time BETWEEN ? AND ?
");
$q->execute([$from, $to]);

$rows = 0;

foreach ($q as $r) {

    $chk = $pdo->prepare("SELECT id FROM crm_recordings WHERE recording_id=?");
    $chk->execute([$r['recording_id']]);

    if (!$chk->rowCount()) {
        $ins = $pdo->prepare("
            INSERT INTO crm_recordings
                (recording_id, lead_id, phone, agent, campaign_id,
                 file, length, start_time)
            VALUES (?,?,?,?,?,?,?,?)
        ");
        $ins->execute([
            $r['recording_id'], $r['lead_id'], $r['phone'], $r['user'], 
            $r['campaign_id'], $r['location'], $r['length_in_sec'], $r['start_time']
        ]);
        $rows++;
    }
}

echo "Recording Sync Completed: $rows new rows.";
