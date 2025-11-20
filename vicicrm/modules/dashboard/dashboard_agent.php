<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
session_start();

$pdo = db();

// PERMISSION CHECK
if (!can_view("dashboard_agent", $pdo)) {
    die("ACCESS DENIED");
}

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Agent Dashboard</title>
<link rel="stylesheet" href="../../assets/style.css">
<link rel="stylesheet" href="../../assets/dashboard.css">
</head>

<body>

<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">

<h2>Agent Dashboard</h2>

<div class="kpi-row">

    <div class="kpi-card kpi-green">
        <div class="kpi-value">
            <?php $s=$pdo->prepare("SELECT COUNT(*) FROM crm_leads WHERE owner_user=?");$s->execute([$user_id]);echo $s->fetchColumn(); ?>
        </div>
        <div class="kpi-label">My Leads</div>
    </div>

    <div class="kpi-card kpi-orange">
        <div class="kpi-value">
            <?php $s=$pdo->prepare("SELECT COUNT(*) FROM crm_leads WHERE owner_user=? AND crm_status='CALLBACK'");$s->execute([$user_id]);echo $s->fetchColumn(); ?>
        </div>
        <div class="kpi-label">My Callbacks</div>
    </div>

    <div class="kpi-card kpi-blue">
        <div class="kpi-value">
            <?php $s=$pdo->prepare("SELECT COUNT(*) FROM crm_manual_dials WHERE user_id=?");$s->execute([$user_id]);echo $s->fetchColumn(); ?>
        </div>
        <div class="kpi-label">My Dials</div>
    </div>

</div>

<div class="card dashboard-box">
<h3>Quick Actions</h3>
<ul>
    <li><a href="../leads/my_leads.php">My Leads</a></li>
    <li><a href="../agent/manual_dial.php">Manual Dial</a></li>
    <li><a href="../../popup.php">Open Blank Popup</a></li>
</ul>
</div>

</div>

</body>
</html>
