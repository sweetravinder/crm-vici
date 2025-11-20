<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
session_start();

$pdo = db();

// PERMISSION CHECK
if (!can_view("dashboard_admin", $pdo)) {
    die("ACCESS DENIED");
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link rel="stylesheet" href="../../assets/style.css">
<link rel="stylesheet" href="../../assets/dashboard.css">
</head>

<body>

<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">

<h2>Admin Dashboard</h2>

<div class="kpi-row">

    <div class="kpi-card kpi-blue">
        <div class="kpi-value">
            <?= $pdo->query("SELECT COUNT(*) FROM crm_users")->fetchColumn() ?>
        </div>
        <div class="kpi-label">CRM Users</div>
    </div>

    <div class="kpi-card kpi-green">
        <div class="kpi-value">
            <?= $pdo->query("SELECT COUNT(*) FROM crm_leads")->fetchColumn() ?>
        </div>
        <div class="kpi-label">Total Leads</div>
    </div>

    <div class="kpi-card kpi-orange">
        <div class="kpi-value">
            <?= $pdo->query("SELECT COUNT(*) FROM crm_manual_dials")->fetchColumn() ?>
        </div>
        <div class="kpi-label">Manual Dials</div>
    </div>

</div>

<div class="card dashboard-box">
<h3>System Management</h3>
<ul>
    <li><a href="../roles/roles_list.php">Roles & Permissions</a></li>
    <li><a href="../settings/settings_system.php">System Settings</a></li>
    <li><a href="../settings/settings_vicidial.php">Dialer API Settings</a></li>
    <li><a href="../settings/settings_themes.php">Theme Settings</a></li>
</ul>
</div>

</div>

</body>
</html>
