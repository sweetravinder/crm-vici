<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
session_start();

$pdo = db();

// PERMISSION CHECK
if (!can_view("recording_view", $pdo) && !can_view("recordings_list", $pdo)) {
    die("ACCESS DENIED");
}

$call_id = $_GET['id'] ?? 0;
if (!$call_id) die("Invalid request");

$stmt = $pdo->prepare("SELECT * FROM crm_calls WHERE call_id=?");
$stmt->execute([$call_id]);
$call = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$call) {
    die("Recording not found");
}

// Path from settings_data table
$settings = $pdo->query("SELECT setting_key, setting_value FROM settings_data")
    ->fetchAll(PDO::FETCH_KEY_PAIR);

$recording_path = rtrim($settings["vicidial_recording_path"] ?? "/var/spool/asterisk/monitor", "/");

// Build file path
$file = $recording_path . "/" . $call['recording_file'];
$url  = "../../recordings/" . $call['recording_file'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Recording Playback</title>
<link rel="stylesheet" href="../../assets/style.css">
</head>

<body>
<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">

<h2>Recording: <?= htmlspecialchars($call['recording_file']) ?></h2>

<div class="card dashboard-box">

<?php if ($call['recording_file']): ?>
    <audio controls style="width:100%;">
        <source src="<?= $url ?>" type="audio/mpeg">
    </audio>
<?php else: ?>
    <p>No recording file found.</p>
<?php endif; ?>

<table class="table">
<tr><th>Agent</th><td><?= $call['agent_user'] ?></td></tr>
<tr><th>Phone</th><td><?= $call['phone_number'] ?></td></tr>
<tr><th>Status</th><td><?= $call['status'] ?></td></tr>
<tr><th>Start</th><td><?= $call['start_time'] ?></td></tr>
<tr><th>Duration</th><td><?= $call['duration'] ?></td></tr>
</table>

</div>

</div>

</body>
</html>
