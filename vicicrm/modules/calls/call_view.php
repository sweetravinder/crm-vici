<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
session_start();

$pdo = db();

if (!can_view("call_view", $pdo)) {
    die("ACCESS DENIED");
}

$id = $_GET['id'] ?? 0;
$src = $_GET['src'] ?? "dialer";

if ($src === "dialer") {
    $sql = "SELECT * FROM crm_calls WHERE call_id=?";
} else {
    $sql = "SELECT *, id AS call_id FROM crm_manual_dials WHERE id=?";
}

$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$call = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$call) die("Call not found");

?>
<!DOCTYPE html>
<html>
<head>
<title>Call Detail</title>
<link rel="stylesheet" href="../../assets/style.css">
</head>
<body>

<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">

<h2>Call #<?= $call['call_id'] ?> (<?= strtoupper($src) ?>)</h2>

<div class="card dashboard-box">
<table class="table">

<tr><th>Lead ID</th><td><a href="#" onclick="window.open('../../popup.php?lead_id=<?= $call['lead_id'] ?>','p','width=400,height=700')"><?= $call['lead_id'] ?></a></td></tr>
<tr><th>Phone</th><td><?= $call['phone_number'] ?></td></tr>
<tr><th>Status</th><td><?= $call['status'] ?></td></tr>
<tr><th>Agent</th><td><?= $call['agent_user'] ?></td></tr>
<tr><th>Type</th><td><?= $call['call_type'] ?></td></tr>
<tr><th>Start</th><td><?= $call['start_time'] ?></td></tr>
<tr><th>End</th><td><?= $call['end_time'] ?></td></tr>
<tr><th>Duration</th><td><?= $call['duration'] ?></td></tr>

<?php if ($src === "dialer" && $call['recording_file']): ?>
<tr><th>Recording</th>
<td>
    <audio controls>
        <source src="../../recordings/<?= $call['recording_file'] ?>" type="audio/mpeg">
    </audio>
</td>
</tr>
<?php endif; ?>

</table>
</div>

</div>

</body>
</html>
