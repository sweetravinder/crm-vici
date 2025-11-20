<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
require_once "../../lib/permissions/FieldPermission.php";
session_start();

$pdo = db();

if (!can_edit("lead_add", $pdo)) {
    die("ACCESS DENIED");
}

$role_id = $_SESSION['role_id'];

function f_input($field, $type, $pdo, $role_id) {
    if (!FieldPermission::canView($role_id, $field, $pdo)) return "";
    $readonly = FieldPermission::canEdit($role_id, $field, $pdo) ? "" : "readonly";
    return "<input type='$type' name='$field'  $readonly>";
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Add Lead</title>
<link rel="stylesheet" href="../../assets/style.css">
</head>

<body>

<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">

<h2>Add Lead</h2>

<form method="post" action="save_lead.php">

<div class="card dashboard-box">

<table class="table">

<?php if (FieldPermission::canView($role_id, "first_name", $pdo)): ?>
<tr><th>First Name</th><td><?= f_input("first_name","text",$pdo,$role_id) ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "last_name", $pdo)): ?>
<tr><th>Last Name</th><td><?= f_input("last_name","text",$pdo,$role_id) ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "phone_number", $pdo)): ?>
<tr><th>Phone</th><td><?= f_input("phone_number","text",$pdo,$role_id) ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "email", $pdo)): ?>
<tr><th>Email</th><td><?= f_input("email","email",$pdo,$role_id) ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "crm_status", $pdo)): ?>
<tr><th>Status</th><td>
    <select name="crm_status" <?= FieldPermission::canEdit($role_id,"crm_status",$pdo)?"":"disabled" ?>>
        <option value="NEW">New</option>
        <option value="PENDING">Pending</option>
        <option value="CALLBACK">Callback</option>
        <option value="SALE">Sale</option>
    </select>
</td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "notes", $pdo)): ?>
<tr><th>Notes</th>
<td>
    <textarea name="notes" rows="5" <?= FieldPermission::canEdit($role_id,"notes",$pdo)?"":"readonly" ?>></textarea>
</td></tr>
<?php endif; ?>

</table>

</div>

<button class="btn btn-success">Create Lead</button>

</form>

</div>
</body>
</html>
