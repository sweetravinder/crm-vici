<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
require_once "../../lib/permissions/FieldPermission.php";
session_start();

$pdo = db();

// PERMISSION CHECK
if (!can_view("leads_list", $pdo)) {
    die("ACCESS DENIED");
}

$role_id = $_SESSION['role_id'];
$user_id = $_SESSION['user_id'];

$search = $_GET['search'] ?? "";
$status = $_GET['status'] ?? "";
$page   = max(1, intval($_GET['page'] ?? 1));
$limit  = 20;
$offset = ($page - 1) * $limit;

// BASIC QUERY
$where = " WHERE 1 ";
$params = [];

if ($role_id != 1 && $role_id != 2) {
    // Agents see only their own leads unless they have special permission
    if (!can_view("my_leads", $pdo)) {
        die("ACCESS DENIED");
    }
    $where .= " AND owner_user = :uid";
    $params["uid"] = $user_id;
}

if ($search !== "") {
    $where .= " AND (first_name LIKE :s OR last_name LIKE :s OR phone_number LIKE :s2)";
    $params["s"]  = "%$search%";
    $params["s2"] = "%$search%";
}

if ($status !== "") {
    $where .= " AND crm_status = :st";
    $params["st"] = $status;
}

// COUNT QUERY
$countSql = "SELECT COUNT(*) FROM crm_leads $where";
$stmt = $pdo->prepare($countSql);
$stmt->execute($params);
$total = $stmt->fetchColumn();

// FETCH ROWS
$sql = "SELECT * FROM crm_leads $where ORDER BY updated_at DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

function openLeadJS($id) {
    return "window.open('../../popup.php?lead_id=$id','leadPopup','width=450,height=700');";
}

?>
<!DOCTYPE html>
<html>
<head>
<title>All Leads</title>
<link rel="stylesheet" href="../../assets/style.css">
<link rel="stylesheet" href="../../assets/dashboard.css">

<script>
function openLead(id) {
    <?= "/* dynamic */" ?>
    window.open("../../popup.php?lead_id=" + id, "leadPopup", "width=450,height=700");
}
</script>

<style>
.lead-row:hover { background:#f9f9f9; cursor:pointer; }
</style>

</head>
<body>

<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">

<h2>All Leads</h2>

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

<div class="card dashboard-box">

<table class="table">
<tr>
    <th>ID</th>

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

    <th>Owner</th>
    <th>Updated</th>

    <?php if (can_edit("lead_edit", $pdo)): ?>
    <th>Edit</th>
    <?php endif; ?>

    <?php if (can_delete("lead_delete", $pdo)): ?>
    <th>Delete</th>
    <?php endif; ?>
</tr>

<?php foreach ($rows as $r): ?>
<tr class="lead-row">

    <td onclick="openLead(<?= $r['lead_id'] ?>)"><?= $r['lead_id'] ?></td>

    <?php if (FieldPermission::canView($role_id, "first_name", $pdo)): ?>
    <td onclick="openLead(<?= $r['lead_id'] ?>)">
        <?= htmlspecialchars($r['first_name']." ".$r['last_name']) ?>
    </td>
    <?php endif; ?>

    <?php if (FieldPermission::canView($role_id, "phone_number", $pdo)): ?>
    <td onclick="openLead(<?= $r['lead_id'] ?>)"><?= $r['phone_number'] ?></td>
    <?php endif; ?>

    <?php if (FieldPermission::canView($role_id, "crm_status", $pdo)): ?>
    <td><?= $r['crm_status'] ?></td>
    <?php endif; ?>

    <?php if (FieldPermission::canView($role_id, "callback_date", $pdo)): ?>
    <td><?= $r['callback_date'] ?: '-' ?></td>
    <?php endif; ?>

    <td>
        <?php
        $u = $pdo->prepare("SELECT full_name FROM crm_users WHERE user_id=?");
        $u->execute([$r['owner_user']]);
        echo $u->fetchColumn() ?: "Unassigned";
        ?>
    </td>

    <td><?= $r['updated_at'] ?></td>

    <?php if (can_edit("lead_edit", $pdo)): ?>
    <td><a href="lead_edit.php?lead_id=<?= $r['lead_id'] ?>" class="btn btn-small btn-warning">Edit</a></td>
    <?php endif; ?>

    <?php if (can_delete("lead_delete", $pdo)): ?>
    <td><a onclick="return confirm('Delete lead?')" href="lead_delete.php?lead_id=<?= $r['lead_id'] ?>" class="btn btn-small btn-danger">Delete</a></td>
    <?php endif; ?>

</tr>
<?php endforeach; ?>

</table>

</div>

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
