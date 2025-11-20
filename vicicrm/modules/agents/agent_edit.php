<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
session_start();

$pdo = db();

if (!can_edit("agent_edit", $pdo)) {
    die("ACCESS DENIED");
}

$uid = $_GET['user_id'] ?? 0;
if (!$uid) die("Invalid user");

$stmt = $pdo->prepare("SELECT * FROM crm_users WHERE user_id=?");
$stmt->execute([$uid]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$u) die("User not found");

// Roles
$roles = $pdo->query("SELECT * FROM crm_roles ORDER BY role_name")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Agent</title>
<link rel="stylesheet" href="../../assets/style.css">
</head>

<body>

<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">

<h2>Edit Agent: <?= htmlspecialchars($u['full_name']) ?></h2>

<form method="post" action="agent_update.php">

<input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">

<div class="card dashboard-box">
<table class="table">

<tr><th>Full Name</th>
<td><input type="text" name="full_name" value="<?= htmlspecialchars($u['full_name']) ?>"></td></tr>

<tr><th>Email</th>
<td><input type="email" name="email" value="<?= htmlspecialchars($u['email']) ?>"></td></tr>

<tr><th>Role</th>
<td>
    <select name="role_id">
        <?php foreach ($roles as $r): ?>
        <option value="<?= $r['role_id'] ?>" <?= $u['role_id']==$r['role_id']?"selected":"" ?>>
            <?= $r['role_name'] ?>
        </option>
        <?php endforeach; ?>
    </select>
</td>
</tr>

<tr><th>Active</th>
<td>
    <select name="active">
        <option value="1" <?= $u['active']?'selected':'' ?>>Active</option>
        <option value="0" <?= !$u['active']?'selected':'' ?>>Inactive</option>
    </select>
</td>
</tr>

<tr><th>New Password</th>
<td><input type="password" name="newpass"></td></tr>

<!-- VICIDIAL MAPPING -->
<tr><th>Vicidial User</th>
<td><input type="text" name="vicidial_user" value="<?= $u['vicidial_user'] ?>"></td></tr>

<tr><th>Vicidial Pass</th>
<td><input type="text" name="vicidial_pass" value="<?= $u['vicidial_pass'] ?>"></td></tr>

</table>
</div>

<button class="btn btn-primary">Save Changes</button>

</form>

</div>
</body>
</html>
