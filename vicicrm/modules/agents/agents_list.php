<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
session_start();

$pdo = db();

// PERMISSION CHECK
if (!can_view("agents_list", $pdo)) {
    die("ACCESS DENIED");
}

$role_id = $_SESSION['role_id'];
$user_id = $_SESSION['user_id'];

// Admin sees all users
if ($role_id == 1) {
    $sql = "SELECT * FROM crm_users ORDER BY full_name";
    $stmt = $pdo->query($sql);
}
// Supervisor sees only agents under them
elseif ($role_id == 2) {
    $stmt = $pdo->prepare("SELECT * FROM crm_users WHERE role_id = 3 ORDER BY full_name");
    $stmt->execute();
}
// Agents cannot view this page normally (RBAC protects)
else {
    die("ACCESS DENIED");
}

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
<title>Agents List</title>
<link rel="stylesheet" href="../../assets/style.css">
</head>

<body>
<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">

<h2>Agents</h2>

<div class="card dashboard-box">
<table class="table">

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Role</th>
    <th>Dialer User</th>
    <th>Status</th>

    <?php if (can_edit("agent_edit", $pdo)): ?>
    <th>Edit</th>
    <?php endif; ?>

    <th>View</th>
</tr>

<?php foreach ($rows as $r): ?>

<tr>
    <td><?= $r['user_id'] ?></td>
    <td><?= htmlspecialchars($r['full_name']) ?></td>
    <td><?= htmlspecialchars($r['email']) ?></td>

    <td>
        <?php
        $rn = $pdo->prepare("SELECT role_name FROM crm_roles WHERE role_id=?");
        $rn->execute([$r['role_id']]);
        echo $rn->fetchColumn();
        ?>
    </td>

    <!-- VICIDIAL MAPPING -->
    <td><?= $r['vicidial_user'] ?: "<span style='color:red'>Not Linked</span>" ?></td>

    <td><?= $r['active'] ? "Active" : "Inactive" ?></td>

    <?php if (can_edit("agent_edit", $pdo)): ?>
    <td><a href="agent_edit.php?user_id=<?= $r['user_id'] ?>" class="btn btn-small btn-warning">Edit</a></td>
    <?php endif; ?>

    <td><a href="agent_view.php?user_id=<?= $r['user_id'] ?>" class="btn btn-small btn-primary">View</a></td>

</tr>

<?php endforeach; ?>

</table>
</div>

</div>
</body>
</html>
