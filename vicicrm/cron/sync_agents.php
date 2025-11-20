<?php
require "../config.php";

// Fetch all vicidial agents
$agents = $pdo->query("
SELECT user, full_name, user_group, active 
FROM vicidial_users
")->fetchAll(PDO::FETCH_ASSOC);

$count = 0;

foreach ($agents as $a) {

    // Check if exists
    $chk = $pdo->prepare("SELECT user FROM crm_agents WHERE user=? LIMIT 1");
    $chk->execute([$a['user']]);

    if ($chk->rowCount()) {
        // Update
        $u = $pdo->prepare("
            UPDATE crm_agents
            SET full_name=?, user_group=?, active=?, updated_at=NOW()
            WHERE user=?
        ");
        $u->execute([$a['full_name'], $a['user_group'], $a['active'], $a['user']]);
    } else {
        // Insert
        $i = $pdo->prepare("
            INSERT INTO crm_agents
                (user, full_name, user_group, active)
            VALUES (?,?,?,?)
        ");
        $i->execute([$a['user'], $a['full_name'], $a['user_group'], $a['active']]);
    }

    $count++;
}

echo "Agent Sync Completed: $count agents.";
