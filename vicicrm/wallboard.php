<?php
require_once "config.php";
session_start();
$pdo = db();

// Wallboard has NO login requirement if you want public screen.
// Enable this if required:
// if (!isset($_SESSION['user_id'])) die("NO ACCESS");

?>
<!DOCTYPE html>
<html>
<head>
<title>Call Center Wallboard</title>

<meta http-equiv="refresh" content="1">

<style>
body {
    margin: 0;
    background: #111;
    color: #fff;
    font-family: Arial;
}

.box {
    padding: 15px;
    background: #222;
    border-radius: 8px;
    margin-bottom: 15px;
}

h1, h2 {
    margin: 5px 0;
    color: #00d4ff;
}

.kpi-box {
    width: 24%;
    float: left;
    background: #222;
    margin-right: 1%;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
}

.kpi-value {
    font-size: 48px;
    font-weight: bold;
    margin-top: 10px;
}

.kpi-label {
    font-size: 20px;
    color: #aaa;
}

.row::after {
    content: "";
    display: block;
    clear: both;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

td, th {
    padding: 10px;
    border-bottom: 1px solid #333;
}

th {
    background: #333;
}

.status-ready { color: #55ff55; }
.status-incall { color: #00aaff; }
.status-pause { color: #ffaa00; }
.status-dead { color: #ff4444; }

</style>

</head>

<body>

<div style="padding:20px;">

<h1>VICICRM LIVE WALLBOARD</h1>
<h2><?php echo date("l, d M Y • H:i:s"); ?></h2>


<!-- ============================
     KPI ROW
============================= -->
<div class="row">

<?php
$kpi = [];

// CALLS TODAY
$kpi['calls_today'] = $pdo->query("
    SELECT COUNT(*) FROM vicidial_log
    WHERE call_date >= CURDATE()
")->fetchColumn();

// ACTIVE CALLS NOW (LIVE CALLS)
$kpi['active_calls'] = $pdo->query("
    SELECT COUNT(*) FROM vicidial_live_agents
    WHERE status = 'INCALL'
")->fetchColumn();

// AGENTS ONLINE
$kpi['agents_online'] = $pdo->query("
    SELECT COUNT(*) FROM vicidial_live_agents
")->fetchColumn();

// ABANDON RATE (LAST 1 HOUR)
$kpi['abandons'] = $pdo->query("
    SELECT COUNT(*) FROM vicidial_closer_log
    WHERE status='DROP' AND call_date >= NOW()-INTERVAL 1 HOUR
")->fetchColumn();

?>

<div class="kpi-box">
    <div class="kpi-value"><?php echo $kpi['calls_today']; ?></div>
    <div class="kpi-label">Calls Today</div>
</div>

<div class="kpi-box">
    <div class="kpi-value"><?php echo $kpi['active_calls']; ?></div>
    <div class="kpi-label">Active Calls</div>
</div>

<div class="kpi-box">
    <div class="kpi-value"><?php echo $kpi['agents_online']; ?></div>
    <div class="kpi-label">Agents Online</div>
</div>

<div class="kpi-box">
    <div class="kpi-value"><?php echo $kpi['abandons']; ?></div>
    <div class="kpi-label">Drops (1 hr)</div>
</div>

</div><!-- row -->


<!-- ============================
     LIVE AGENT STATUS TABLE
============================= -->
<div class="box" style="margin-top:30px;">
<h2>Agent Status</h2>

<?php
$sql = "
SELECT 
    vla.user,
    u.full_name,
    vla.campaign_id,
    vla.status,
    vla.uniqueid,
    vla.callerid,
    vla.last_call_time
FROM vicidial_live_agents vla
LEFT JOIN crm_users u ON u.vicidial_user = vla.user
ORDER BY vla.user
";

$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<table>
<tr>
    <th>User</th>
    <th>Name</th>
    <th>Campaign</th>
    <th>Status</th>
    <th>CallerID</th>
    <th>Time in Status</th>
</tr>

<?php foreach ($rows as $r): 
    $t = time() - strtotime($r['last_call_time']);
    $t_f = gmdate("H:i:s", $t);

    $cls = "status-ready";
    if ($r['status'] == "INCALL") $cls = "status-incall";
    if ($r['status'] == "PAUSED") $cls = "status-pause";
    if ($r['status'] == "DEAD")   $cls = "status-dead";
?>

<tr>
    <td><?php echo $r['user']; ?></td>
    <td><?php echo $r['full_name']; ?></td>
    <td><?php echo $r['campaign_id']; ?></td>
    <td class="<?php echo $cls; ?>"><?php echo $r['status']; ?></td>
    <td><?php echo $r['callerid']; ?></td>
    <td><?php echo $t_f; ?></td>
</tr>

<?php endforeach; ?>

</table>

</div><!-- box -->


</div><!-- page -->

</body>
</html>
