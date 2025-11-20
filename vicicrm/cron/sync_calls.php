<?php
require "../config.php";

$from = date("Y-m-d 00:00:00");
$to   = date("Y-m-d 23:59:59");

// OUTBOUND CALLS
$out = $pdo->prepare("
SELECT uniqueid, lead_id, phone_number, user, status, length_in_sec,
       call_date AS call_time, campaign_id
FROM vicidial_log
WHERE call_date BETWEEN ? AND ?
");
$out->execute([$from, $to]);

$rows = 0;

foreach ($out as $c) {

    $exists = $pdo->prepare("SELECT id FROM crm_calls WHERE uniqueid=? LIMIT 1");
    $exists->execute([$c['uniqueid']]);

    if (!$exists->rowCount()) {
        $ins = $pdo->prepare("
            INSERT INTO crm_calls
                (uniqueid, lead_id, phone, agent, status, type, duration,
                 call_time, campaign_id)
            VALUES (?,?,?,?,?,'OUT',?,?,?)
        ");
        $ins->execute([
            $c['uniqueid'], $c['lead_id'], $c['phone_number'], $c['user'],
            $c['status'], $c['length_in_sec'], $c['call_time'], $c['campaign_id']
        ]);
        $rows++;
    }
}

// INBOUND CALLS
$in = $pdo->prepare("
SELECT uniqueid, lead_id, phone_number, user, status, length_in_sec,
       call_date AS call_time, campaign_id
FROM vicidial_closer_log
WHERE call_date BETWEEN ? AND ?
");
$in->execute([$from, $to]);

foreach ($in as $c) {

    $exists = $pdo->prepare("SELECT id FROM crm_calls WHERE uniqueid=? LIMIT 1");
    $exists->execute([$c['uniqueid']]);

    if (!$exists->rowCount()) {
        $ins = $pdo->prepare("
            INSERT INTO crm_calls
                (uniqueid, lead_id, phone, agent, status, type, duration,
                 call_time, campaign_id)
            VALUES (?,?,?,?,?,'IN',?,?,?)
        ");
        $ins->execute([
            $c['uniqueid'], $c['lead_id'], $c['phone_number'], $c['user'],
            $c['status'], $c['length_in_sec'], $c['call_time'], $c['campaign_id']
        ]);
        $rows++;
    }
}

echo "Call Sync Completed: $rows new records.";
