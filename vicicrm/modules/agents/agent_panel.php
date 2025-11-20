<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
session_start();

$pdo = db();
$user_id = $_SESSION['user_id'];

$q = $pdo->prepare("SELECT full_name, vicidial_user FROM crm_users WHERE user_id=?");
$q->execute([$user_id]);
$u = $q->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
<title>Agent Panel</title>
<link rel="stylesheet" href="../../assets/style.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function refreshStatus() {
    $.get("dialer_status.php", function(res){
        var r = JSON.parse(res);
        $("#status_div").html(
            "<b>Agent:</b> " + r.agent_user + 
            "<br><b>Last Call:</b> " + (r.last_call ? r.last_call.phone_number : "None")
        );
    });
}
setInterval(refreshStatus, 3000);
</script>
</head>

<body>

<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">
<h2>Agent Panel</h2>

<div class="card dashboard-box" id="status_div">
Loading...
</div>

</div>
</body>
</html>
