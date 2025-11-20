<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
session_start();

$pdo = db();

// PERMISSION CHECK
if (!can_view("dashboard_supervisor", $pdo)) {
    die("ACCESS DENIED");
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Supervisor Dashboard</title>
<link rel="stylesheet" href="../../assets/style.css">
<link rel="stylesheet" href="../../assets/dashboard.css">
</head>

<body>

<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">

<h2>Supervisor Dashboard</h2>

<div class="kpi-row">
    <div class="kpi-card kpi-purple">
        <div class="kpi-value">
            <?= $pdo->query("SELECT COUNT(*) FROM crm_users WHERE role_id=3")->fetchColumn() ?>
        </div>
        <div class="kpi-label">Agents</div>
    </div>

    <div class="kpi-card kpi-blue">
        <div class="kpi-value">
            <?= $pdo->query("SELECT COUNT(*) FROM crm_leads WHERE crm_status='PENDING'")->fetchColumn() ?>
        </div>
        <div class="kpi-label">Pending Leads</div>
    </div>

    <div class="kpi-card kpi-green">
        <div class="kpi-value">
            <?= $pdo->query("SELECT COUNT(*) FROM crm_leads WHERE crm_status='CALLBACK'")->fetchColumn() ?>
        </div>
        <div class="kpi-label">Callbacks</div>
    </div>
</div>

<div class="card dashboard-box">
<h3>Supervisor Tools</h3>
<ul>
    <li><a href="../supervisor/live_agents.php">Live Agents</a></li>
    <li><a href="../supervisor/monitor_actions.php">Monitor/Whisper/Barge</a></li>
    <li><a href="../leads/leads_list.php">All Leads</a></li>
    <li><a href="../reports/agent_report.php">Agent Performance</a></li>
</ul>
</div>

</div>

</body>
</html>
