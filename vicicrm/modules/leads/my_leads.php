<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
require_once "../../lib/permissions/FieldPermission.php";
session_start();

$pdo = db();

// PERMISSION CHECK
if (!can_view("my_leads", $pdo)) {
    die("ACCESS DENIED");
}

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

$search = $_GET['search'] ?? "";
$status = $_GET['status'] ?? "";
$page   = max(1, intval($_GET['page'] ?? 1));
$limit  = 20;
$offset = ($page - 1) * $limit;

// BASIC QUERY
$where = " WHERE owner_user = :uid ";
$params = ["uid" => $user_id];

if ($search !== "") {
    $where .= " AND (first_name LIKE :s OR last_name LIKE :s OR phone_number LIKE :s2)";
    $params["s"]  = "%$search%";
    $params["s2"] = "%$search%";
}

if ($status !== "") {
    $where .= " AND crm_status = :st";
    $params["st"] = $status;
}

// COUNT
$countSql = "SELECT COUNT(*) FROM crm_leads $where";
$stmt = $pdo->prepare($countSql);
$stmt->execute($params);
$total = $stmt->fetchColumn();

// FETCH ROWS
$sql = "SELECT * FROM crm_leads $where ORDER BY updated_at DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
<title>My Leads</title>
<link rel="stylesheet" href="../../assets/style.css">
<link rel="stylesheet" href="../../assets/dashboard.css">

<script>
function openLead(id) {
    window.open("../../popup.php?lead_id=" + id, "leadPopup", "width=450,height=700");
}
</script>

<style>
.lead-row:hover { background: #f9f9f9; cursor:pointer; }
</style>

</head>

<body>

<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">

<h2>My Leads</h2>

<!-- FILTERS -->
<div class="card dashboard-box">
<form method="get">
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search..." style="width:220px;">
    <select name="status">
        <option value="">All Status</option>
        <option value="NEW"      <?= $status=='NEW'?'selected':'' ?>>New</option>
        <option value="PENDING"  <?= $status=='PENDING'?'selected':'' ?>>Pending</option>
        <option value="CALLBACK" <?= $status=='CALLBACK'?'selected':'' ?>>Callback</option>
        <option value="SALE"     <?= $status=='SALE'?'selected':'' ?>>Sale</option>
    </select>
    <button class="btn btn-primary">Search</button>
</form>
</div>

<!-- TABLE -->
<div class="card dashboard-box">
<table class="table">

<tr>
    <th>Lead ID</th>

    <?php if (FieldPermission::canView($role_id, "first_name", $pdo)): ?>
    <th>Name</th>
    <?php endif; ?>

    <?php if (FieldPermission::canView($role_id, "phone_number", $pdo)): ?>
    <th>Phone</th>
    <?php endif; ?>

    <?php if (FieldPermission::canView($role_id, "crm_status", $pdo)): ?>
    <th>Status</th>
    <?php endif; ?>

    <?php if (FieldPermission::canView($role_id, "callback_date", $pdo)): ?>
    <th>Callback</th>
    <?php endif; ?>

    <th>Updated</th>
</tr>

<?php foreach ($rows as $r): ?>
<tr class="lead-row" onclick="openLead(<?= $r['lead_id'] ?>)">
    <td><?= $r['lead_id'] ?></td>

    <?php if (FieldPermission::canView($role_id, "first_name", $pdo)): ?>
    <td><?= $r['first_name']." ".$r['last_name'] ?></td>
    <?php endif; ?>

    <?php if (FieldPermission::canView($role_id, "phone_number", $pdo)): ?>
    <td><?= $r['phone_number'] ?></td>
    <?php endif; ?>

    <?php if (FieldPermission::canView($role_id, "crm_status", $pdo)): ?>
    <td><?= $r['crm_status'] ?></td>
    <?php endif; ?>

    <?php if (FieldPermission::canView($role_id, "callback_date", $pdo)): ?>
    <td><?= $r['callback_date'] ?: '-' ?></td>
    <?php endif; ?>

    <td><?= $r['updated_at'] ?></td>
</tr>
<?php endforeach; ?>

</table>
</div>

<!-- PAGINATION -->
<div class="card dashboard-box">
<?php
$totalPages = ceil($total / $limit);
for ($i=1;$i<=$totalPages;$i++):
    if ($i == $page) echo "<b>$i</b> ";
    else echo "<a href='?page=$i&search=$search&status=$status'>$i</a> ";
endfor;
?>
</div>

</div>

</body>
</html>
