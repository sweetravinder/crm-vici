<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
session_start();

$pdo = db();

// Permission
if (!can_view("dialer_login", $pdo)) {
    die("ACCESS DENIED");
}

$user_id = $_SESSION['user_id'];

// Fetch CRM agent data
$uStmt = $pdo->prepare("SELECT full_name, vicidial_user, vicidial_pass FROM crm_users WHERE user_id=?");
$uStmt->execute([$user_id]);
$agent = $uStmt->fetch(PDO::FETCH_ASSOC);

// Load cached campaigns
$cStmt = $pdo->prepare("SELECT * FROM crm_campaigns WHERE active = 1 ORDER BY campaign_name");
$cStmt->execute();
$campaigns = $cStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>Dialer Login</title>
<link rel="stylesheet" href="../../assets/style.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">
<h2>Dialer Login</h2>

<div class="card dashboard-box">

<table class="table">
<tr><th>Agent</th><td><?= htmlspecialchars($agent["full_name"]) ?></td></tr>
<tr><th>Vicidial User</th><td><?= $agent["vicidial_user"] ?></td></tr>
</table>

<hr>

<h3>Login Details</h3>

<table class="table">

<tr>
    <th>Phone Login</th>
    <td><input type="text" id="phone_login" style="width:200px;"></td>
</tr>

<tr>
    <th>Phone Password</th>
    <td><input type="password" id="phone_pass" style="width:200px;"></td>
</tr>

<tr>
    <th>Campaign</th>
    <td>
        <select id="campaign" style="width:220px;">
            <?php foreach ($campaigns as $c): ?>
            <option value="<?= $c['campaign_id'] ?>"><?= $c['campaign_name'] ?></option>
            <?php endforeach; ?>
        </select>
    </td>
</tr>

<tr>
    <td colspan="2" style="text-align:center;">
        <button class="btn btn-success" onclick="loginDialer()">Login to Dialer</button>
    </td>
</tr>

</table>

<div id="login_result" style="padding:10px;color:blue;"></div>

</div>

</div>

<script>
function loginDialer() {
    $("#login_result").html("Processing...");

    $.post("agent_login_handler.php", {
        phone_login: $("#phone_login").val(),
        phone_pass: $("#phone_pass").val(),
        campaign: $("#campaign").val()
    }, function(res) {
        try {
            var r = JSON.parse(res);
            $("#login_result").html(r.msg);
        } catch (e) {
            $("#login_result").html("Unexpected response");
        }
    });
}
</script>

</body>
</html>
