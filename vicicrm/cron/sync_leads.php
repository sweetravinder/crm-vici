<?php
require "../config.php";

// Fetch leads updated in last 24 hours
$sql = "
SELECT lead_id, phone_number, alt_phone, first_name, last_name, email,
       status, owner_user, modify_date
FROM vicidial_list
WHERE modify_date >= NOW() - INTERVAL 1 DAY
ORDER BY modify_date DESC
";

$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $l) {

    // Check if exists
    $chk = $pdo->prepare("SELECT lead_id FROM crm_leads WHERE lead_id=? LIMIT 1");
    $chk->execute([$l['lead_id']]);

    if ($chk->rowCount()) {
        // UPDATE
        $u = $pdo->prepare("
            UPDATE crm_leads SET
                phone=?, alt_phone=?, first_name=?, last_name=?, email=?,
                status=?, owner_user=?, modify_date=?
            WHERE lead_id=?
        ");
        $u->execute([
            $l['phone_number'], $l['alt_phone'], $l['first_name'], $l['last_name'],
            $l['email'], $l['status'], $l['owner_user'], $l['modify_date'], $l['lead_id']
        ]);

    } else {
        // INSERT
        $i = $pdo->prepare("
            INSERT INTO crm_leads
                (lead_id, phone, alt_phone, first_name, last_name, email,
                 status, owner_user, modify_date)
            VALUES (?,?,?,?,?,?,?,?,?)
        ");
        $i->execute([
            $l['lead_id'], $l['phone_number'], $l['alt_phone'], $l['first_name'],
            $l['last_name'], $l['email'], $l['status'], $l['owner_user'], $l['modify_date']
        ]);
    }
}

echo "Lead Sync Completed: " . count($rows) . " leads.";
