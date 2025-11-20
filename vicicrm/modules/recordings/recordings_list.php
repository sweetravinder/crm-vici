<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
session_start();

$pdo = db();

// PERMISSION CHECK
if (!can_view("recordings_list", $pdo)) {
    die("ACCESS DENIED");
}

$role_id = $_SESSION["role_id"];
$user_id = $_SESSION["user_id"];

// Filters
$search = $_GET['search'] ?? "";
$date   = $_GET['date']   ?? "";
$page   = max(1, intval($_GET['page'] ?? 1));
$limit  = 25;
$offset = ($page - 1) * $limit;

// BASIC FILTER
$where = " WHERE recording_file IS NOT NULL AND recording_file != '' ";
$params = [];

// Agent restriction (only see their own)
if ($role_id == 3) {
    $where .= " AND agent_user = :agent ";
    $params["agent"] = $user_id;
}

// Search filter
if ($search !== "") {
    $where .= " AND (phone_number LIKE :s OR agent_user LIKE :s2)";
    $params["s"]  = "%$search%";
    $params["s2"] = "%$search%";
}

// Date filter
if ($date !== "") {
    $where .= " AND DATE(start_time) = :dt ";
    $params["dt"] = $date;
}

// Get count
$cSql = "SELECT COUNT(*) FROM crm_calls $where";
$cStmt = $pdo->prepare($cSql);
$cStmt->execute($params);
$total = $cStmt->fetchColumn();

// Fetch results
$sql = "
    SELECT * FROM crm_calls
    $where
    ORDER BY start_time DESC
    LIMIT $limit OFFSET $offset
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<title>Recordings</title>
<link rel="stylesheet" href="../../assets/style.css">
</head>

<body>

<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">

<h2>Call Recordings</h2>

<div class="card dashboard-box">
<form method="get">
    <input type="text" name="search" placeholder="Phone / Agent" value="<?= htmlspecialchars($search) ?>" style="width:220px;">
    <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">
    <button class="btn btn-primary">Filter</button>
</form>
</div>

<div class="card dashboard-box">
<table class="table">

<tr>
    <th>File</th>
    <th>Agent</th>
    <th>Phone</th>
    <th>Status</th>
    <th>Start</th>
    <th>Duration</th>
    <th>Play</th>
</tr>

<?php foreach ($rows as $r): ?>
<tr>

<td><?= htmlspecialchars($r['recording_file']) ?></td>
<td><?= htmlspecialchars($r['agent_user']) ?></td>
<td><?= htmlspecialchars($r['phone_number']) ?></td>
<td><?= htmlspecialchars($r['status']) ?></td>
<td><?= $r['start_time'] ?></td>
<td><?= $r['duration'] ?></td>

<td>
    <a class="btn btn-small btn-success"
       href="recording_view.php?id=<?= $r['call_id'] ?>">
       Play
    </a>
</td>

</tr>
<?php endforeach; ?>

</table>
</div>

<div class="card dashboard-box">
<?php
$totalPages = ceil($total / $limit);
for ($i=1;$i<=$totalPages;$i++){
    if ($i==$page) echo "<b>$i</b> ";
    else echo "<a href='?page=$i&search=$search&date=$date'>$i</a> ";
}
?>
</div>

</div>

</body>
</html>
