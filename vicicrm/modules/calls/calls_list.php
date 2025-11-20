<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
require_once "../../lib/permissions/FieldPermission.php";
session_start();

$pdo = db();

// PERMISSION CHECK
if (!can_view("calls_list", $pdo)) {
    die("ACCESS DENIED");
}

$role_id = $_SESSION['role_id'];
$user_id = $_SESSION['user_id'];

$page   = max(1, intval($_GET['page'] ?? 1));
$limit  = 25;
$offset = ($page - 1) * $limit;

// Supervisors/Admins see everything
if ($role_id == 1 || $role_id == 2) {
    $filter = " WHERE 1 ";
    $params = [];
} else {
    // Agents only see their calls
    $filter = " WHERE agent_user = :user OR user_id = :user ";
    $params = ["user" => $user_id];
}

// Hybrid call logs: merge crm_calls + crm_manual_dials
$sql = "
    SELECT 
        'dialer' AS source,
        c.call_id AS id,
        c.lead_id,
        c.agent_user,
        c.phone_number,
        c.status,
        c.call_type,
        c.recording_file,
        c.start_time,
        c.end_time,
        c.duration
    FROM crm_calls c
    $filter

    UNION ALL

    SELECT
        'manual' AS source,
        m.id AS id,
        m.lead_id,
        m.user_id AS agent_user,
        m.phone_number,
        m.call_status AS status,
        'manual' AS call_type,
        NULL AS recording_file,
        m.created_at AS start_time,
        m.created_at AS end_time,
        NULL AS duration
    FROM crm_manual_dials m
    $filter

    ORDER BY start_time DESC
    LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count
$countSql = "
    SELECT COUNT(*) FROM crm_calls c $filter
    UNION ALL
    SELECT COUNT(*) FROM crm_manual_dials m $filter
";
$c = $pdo->prepare($countSql);
$c->execute($params);
$totalParts = $c->fetchAll(PDO::FETCH_COLUMN);
$total = array_sum($totalParts);
?>
<!DOCTYPE html>
<html>
<head>
<title>Call Logs</title>
<link rel="stylesheet" href="../../assets/style.css">
<script>
function openLead(id) {
    if (!id) return;
    window.open("../../popup.php?lead_id=" + id, "leadPopup", "width=450,height=700");
}
</script>

<style>
.call-row:hover { background:#f2f2f2; cursor:pointer; }
</style>
</head>

<body>
<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">
<h2>Call Logs</h2>

<div class="card dashboard-box">
<table class="table">

<tr>
    <th>ID</th>

    <?php if (FieldPermission::canView($role_id, "phone_number", $pdo)): ?>
    <th>Phone</th>
    <?php endif; ?>

    <th>Status</th>
    <th>Agent</th>
    <th>Type</th>
    <th>Time</th>

    <?php if (can_view("recordings_list", $pdo)): ?>
    <th>Recording</th>
    <?php endif; ?>

</tr>

<?php foreach ($rows as $r): ?>
<tr class="call-row" onclick="openLead(<?= $r['lead_id'] ?>)">

    <td><?= $r['id'] ?></td>

    <?php if (FieldPermission::canView($role_id, "phone_number", $pdo)): ?>
    <td><?= $r['phone_number'] ?></td>
    <?php endif; ?>

    <td><?= $r['status'] ?></td>
    <td><?= $r['agent_user'] ?></td>
    <td><?= $r['call_type'] ?></td>
    <td><?= $r['start_time'] ?></td>

    <?php if (can_view("recordings_list", $pdo)): ?>
    <td>
        <?php if ($r['recording_file']): ?>
            <a href="../recordings/recording_view.php?file=<?= urlencode($r['recording_file']) ?>">Play</a>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
    <?php endif; ?>

</tr>
<?php endforeach; ?>

</table>
</div>

<div class="card dashboard-box">
<?php
$totalPages = ceil($total / $limit);
for ($i=1;$i<=$totalPages;$i++){
    if ($i == $page) echo "<b>$i</b> ";
    else echo "<a href='?page=$i'>$i</a> ";
}
?>
</div>

</div>
</body>
</html>
