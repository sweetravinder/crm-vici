<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
require_once "../../lib/permissions/FieldPermission.php";
session_start();

$pdo = db();

if (!can_edit("lead_edit", $pdo)) {
    die("ACCESS DENIED");
}

$lead_id = $_GET['lead_id'] ?? 0;
if (!$lead_id) die("Invalid lead");

$stmt = $pdo->prepare("SELECT * FROM crm_leads WHERE lead_id=?");
$stmt->execute([$lead_id]);
$lead = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lead) die("Lead not found");

$role_id = $_SESSION['role_id'];

// READONLY or EDITABLE
function f_input($field, $type, $value, $pdo, $role_id) {
    $readonly = FieldPermission::canEdit($role_id, $field, $pdo) ? "" : "readonly";
    return "<input type='$type' name='$field' value='".htmlspecialchars($value)."' $readonly>";
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Lead</title>
<link rel="stylesheet" href="../../assets/style.css">
</head>
<body>

<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">

<h2>Edit Lead #<?= $lead_id ?></h2>

<form method="post" action="save_lead.php">

<input type="hidden" name="lead_id" value="<?= $lead_id ?>">

<div class="card dashboard-box">
<table class="table">

<?php if (FieldPermission::canView($role_id, "first_name", $pdo)): ?>
<tr><th>First Name</th><td><?= f_input("first_name","text",$lead['first_name'],$pdo,$role_id) ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "last_name", $pdo)): ?>
<tr><th>Last Name</th><td><?= f_input("last_name","text",$lead['last_name'],$pdo,$role_id) ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "phone_number", $pdo)): ?>
<tr><th>Phone</th><td><?= f_input("phone_number","text",$lead['phone_number'],$pdo,$role_id) ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "email", $pdo)): ?>
<tr><th>Email</th><td><?= f_input("email","email",$lead['email'],$pdo,$role_id) ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "address1", $pdo)): ?>
<tr><th>Address 1</th><td><?= f_input("address1","text",$lead['address1'],$pdo,$role_id) ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "address2", $pdo)): ?>
<tr><th>Address 2</th><td><?= f_input("address2","text",$lead['address2'],$pdo,$role_id) ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "city", $pdo)): ?>
<tr><th>City</th><td><?= f_input("city","text",$lead['city'],$pdo,$role_id) ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "state", $pdo)): ?>
<tr><th>State</th><td><?= f_input("state","text",$lead['state'],$pdo,$role_id) ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "postal_code", $pdo)): ?>
<tr><th>Postal Code</th><td><?= f_input("postal_code","text",$lead['postal_code'],$pdo,$role_id) ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "crm_status", $pdo)): ?>
<tr><th>Status</th><td>
    <select name="crm_status" <?= FieldPermission::canEdit($role_id,"crm_status",$pdo)?"":"disabled" ?>>
        <option value="NEW"      <?= $lead['crm_status']=="NEW"?"selected":"" ?>>New</option>
        <option value="PENDING"  <?= $lead['crm_status']=="PENDING"?"selected":"" ?>>Pending</option>
        <option value="CALLBACK" <?= $lead['crm_status']=="CALLBACK"?"selected":"" ?>>Callback</option>
        <option value="SALE"     <?= $lead['crm_status']=="SALE"?"selected":"" ?>>Sale</option>
    </select>
</td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "callback_date", $pdo)): ?>
<tr><th>Callback Date</th><td><?= f_input("callback_date","datetime-local",$lead['callback_date'],$pdo,$role_id) ?></td></tr>
<?php endif; ?>

<?php if (FieldPermission::canView($role_id, "notes", $pdo)): ?>
<tr>
    <th>Notes</th>
    <td>
        <textarea name="notes" rows="5" <?= FieldPermission::canEdit($role_id,"notes",$pdo)?"":"readonly" ?>><?= htmlspecialchars($lead['notes']) ?></textarea>
    </td>
</tr>
<?php endif; ?>

</table>
</div>

<button class="btn btn-primary">Save Changes</button>

</form>

</div>

</body>
</html>
