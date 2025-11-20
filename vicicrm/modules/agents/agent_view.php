<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
session_start();

$pdo = db();

if (!can_view("agent_view", $pdo)) {
    die("ACCESS DENIED");
}

$uid = $_GET['user_id'] ?? 0;
if (!$uid) die("Invalid user");

$stmt = $pdo->prepare("SELECT * FROM crm_users WHERE user_id=?");
$stmt->execute([$uid]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$u) die("User not found");

?>
<!DOCTYPE html>
<html>
<head>
<title>Agent Details</title>
<link rel="stylesheet" href="../../assets/style.css">
</head>

<body>
<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">

<h2>Agent: <?= htmlspecialchars($u['full_name']) ?></h2>

<div class="card dashboard-box">
<table class="table">

<tr><th>User ID</th><td><?= $u['user_id'] ?></td></tr>
<tr><th>Name</th><td><?= htmlspecialchars($u['full_name']) ?></td></tr>
<tr><th>Email</th><td><?= htmlspecialchars($u['email']) ?></td></tr>
<tr><th>Role</th><td>
    <?php
    $rn = $pdo->prepare("SELECT role_name FROM crm_roles WHERE role_id=?");
    $rn->execute([$u['role_id']]);
    echo $rn->fetchColumn();
    ?>
</td></tr>

<tr><th>Status</th><td><?= $u['active'] ? "Active" : "Inactive" ?></td></tr>

<tr><th>VICIDIAL User</th>
<td><?= $u['vicidial_user'] ?: "<span style='color:red'>Not Linked</span>" ?></td></tr>

<tr><th>VICIDIAL Pass</th><td><?= $u['vicidial_pass'] ?: "-" ?></td></tr>

</table>
</div>

<?php if (can_edit("agent_edit", $pdo)): ?>
<a href="agent_edit.php?user_id=<?= $u['user_id'] ?>" class="btn btn-warning">Edit Agent</a>
<?php endif; ?>

</div>
</body>
</html>
