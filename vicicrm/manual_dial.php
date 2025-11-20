<?php
require_once "config.php";
session_start();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role_id'], [1,3])) {
    die("ACCESS DENIED");
}

$pdo = db();

// Check if agent is logged into dialer
$vic_user = $_SESSION['vicidial_user'] ?? null;

?>
<!DOCTYPE html>
<html>
<head>
<title>Manual Dial</title>
<link rel="stylesheet" href="assets/style.css">
<link rel="stylesheet" href="assets/dashboard.css">
</head>

<body>
<?php include "header.php"; ?>
<?php include "sidebar.php"; ?>

<div class="content">

<h2>Manual Dial</h2>

<!-- Dialer Login Status Box -->
<div class="card dashboard-box">
<h3>Dialer Status</h3>

<div id="dialer-status">Checking...</div>

<script>
function checkDialerStatus() {
    fetch("modules/agent/agent_realtime.php")
        .then(r => r.json())
        .then(js => {
            let box = document.getElementById("dialer-status");
            if (js.status === "OK") {
                box.innerHTML = `
                    <b>Status:</b> ${js.agent.agent_status}<br>
                    <b>Campaign:</b> ${js.agent.campaign}<br>
                    <b>Time in Status:</b> ${js.agent.time_in_state_f}
                `;
            } else {
                box.innerHTML = "<span style='color:red;'>Not logged into dialer.</span>";
            }
        });
}
setInterval(checkDialerStatus, 2000);
checkDialerStatus();
</script>

</div>


<!-- Manual Dial Form -->
<div class="card dashboard-box">
<h3>Dial Number</h3>

<form id="manualDialForm">

<label>Phone Number:</label><br>
<input type="text" id="phone" name="phone" style="width:250px;" required><br><br>

<label>Lead ID (Optional):</label><br>
<input type="text" id="lead_id" name="lead_id" style="width:100px;"><br><br>

<button type="button" onclick="doManualDial()" class="btn btn-primary">Dial Now</button>

</form>

<div id="dial-result" style="margin-top:20px;"></div>

<script>
function doManualDial() {
    let phone = document.getElementById("phone").value.trim();
    let lead  = document.getElementById("lead_id").value.trim();

    if (phone === "") {
        alert("Enter phone number.");
        return;
    }

    fetch("ajax/manual_dial_process.php?phone=" + encodeURIComponent(phone) + "&lead=" + encodeURIComponent(lead))
        .then(r => r.json())
        .then(js => {
            let box = document.getElementById("dial-result");

            if (js.status === "OK") {
                box.innerHTML = `
                    <div style='color:green;font-weight:bold;'>Call Launched Successfully!</div>
                    Call ID: ${js.uniqueid}
                `;
                // Auto-open popup after 1.5 sec
                setTimeout(() => {
                    window.open("popup.php?lead_id=" + js.lead_id, "leadPopup", "width=450,height=700");
                }, 1500);
            } else {
                box.innerHTML = "<div style='color:red;'>" + js.message + "</div>";
            }
        });
}
</script>

</div>

<!-- Last Dialed Numbers -->
<div class="card dashboard-box">
<h3>Recent Manual Dials</h3>

<?php
$last = $pdo->prepare("
    SELECT phone, created_at FROM crm_manual_dials
    WHERE user_id = ?
    ORDER BY created_at DESC
    LIMIT 10
");
$last->execute([$_SESSION['user_id']]);
$rows = $last->fetchAll(PDO::FETCH_ASSOC);
?>

<table class="table">
<tr>
    <th>Phone</th>
    <th>Time</th>
</tr>
<?php foreach ($rows as $r): ?>
<tr>
    <td><?= $r['phone'] ?></td>
    <td><?= $r['created_at'] ?></td>
</tr>
<?php endforeach; ?>
</table>

</div>

</div><!-- content -->
</body>
</html>
