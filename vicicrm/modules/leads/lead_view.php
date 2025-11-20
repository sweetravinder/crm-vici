<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
require_once "../../lib/permissions/FieldPermission.php";
session_start();

$pdo = db();

if (!can_view("lead_view", $pdo)) {
    die("ACCESS DENIED");
}

$lead_id = $_GET['lead_id'] ?? 0;
if (!$lead_id) die("Invalid lead");

$stmt = $pdo->prepare("SELECT * FROM crm_leads WHERE lead_id=?");
$stmt->execute([$lead_id]);
$lead = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lead) die("Lead not found");

$role_id = $_SESSION['role_id'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Lead Details</title>
<link rel="stylesheet" href="../../assets/style.css">
</head>
<body>

<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">
<h2>Lead #<?= $lead_id ?></h2>

<div class="card dashboard-box">

<table class="table">

<?php if (FieldPermission::canView($role_id, "first_name", $pdo)): ?>
<tr><th>Name</th><td><?= $lead['first_name']." ".$lead['last_name'] ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "phone_number", $pdo)): ?>
<tr><th>Phone</th><td><?= $lead['phone_number'] ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "email", $pdo)): ?>
<tr><th>Email</th><td><?= $lead['email'] ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "address1", $pdo)): ?>
<tr><th>Address</th><td><?= $lead['address1']." ".$lead['address2'] ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "crm_status", $pdo)): ?>
<tr><th>Status</th><td><?= $lead['crm_status'] ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "callback_date", $pdo)): ?>
<tr><th>Callback</th><td><?= $lead['callback_date'] ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "notes", $pdo)): ?>
<tr><th>Notes</th><td><?= nl2br($lead['notes']) ?></td></tr>
<?php endif; ?>

</table>

</div>

<?php if (can_edit("lead_edit", $pdo)): ?>
<a href="lead_edit.php?lead_id=<?= $lead_id ?>" class="btn btn-warning">Edit Lead</a>
<?php endif; ?>

<?php if (can_delete("lead_delete", $pdo)): ?>
<a onclick="return confirm('Delete lead?')" href="lead_delete.php?lead_id=<?= $lead_id ?>" class="btn btn-danger">Delete Lead</a>
<?php endif; ?>

</div>

</body>
</html>
