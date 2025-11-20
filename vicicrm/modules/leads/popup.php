<?php
require "../../config.php";
require "../../lib/auth/Auth.php";
require "../../lib/permissions/helpers.php";

Auth::start();
if (!Auth::isLoggedIn()) { die("NOT LOGGED IN"); }

$user = Auth::user($pdo);

$lead_id  = $_GET['lead_id'] ?? '';
$phone    = $_GET['phone'] ?? '';
$agent    = $_GET['agent'] ?? $user['username'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM vicidial_list WHERE lead_id=? LIMIT 1");
$stmt->execute([$lead_id]);
$lead = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head><title>Lead Popup</title></head>
<body>
<div style="padding:10px;">
<h2>Lead #<?=htmlspecialchars($lead_id)?> - <?=htmlspecialchars($lead['first_name'] ?? '')?> <?=htmlspecialchars($lead['last_name'] ?? '')?></h2>
<p><b>Phone:</b> <?=htmlspecialchars($phone)?></p>
<p><b>Agent:</b> <?=htmlspecialchars($agent)?></p>

<div id="historyBox">
Loading history...
</div>

<div id="recordingBox">Loading recordings...</div>
<div id="noteBox">Loading notes...</div>

<button onclick="actionAgent('hangup')">Hangup</button>
<button onclick="actionAgent('pause')">Pause</button>
<button onclick="actionAgent('resume')">Resume</button>

<script>
function loadHistory() {
    fetch("ajax/load_history.php?lead_id=<?=$lead_id?>")
    .then(r=>r.text()).then(html=>document.getElementById("historyBox").innerHTML=html);
}
function loadRecordings() {
    fetch("ajax/load_recordings.php?lead_id=<?=$lead_id?>")
    .then(r=>r.text()).then(html=>document.getElementById("recordingBox").innerHTML=html);
}
function loadNotes() {
    fetch("ajax/load_notes.php?lead_id=<?=$lead_id?>")
    .then(r=>r.text()).then(html=>document.getElementById("noteBox").innerHTML=html);
}
function actionAgent(act) {
    fetch("../../ajax/agent_action.php?action="+act)
    .then(r=>r.text()).then(alert);
}

loadHistory(); loadRecordings(); loadNotes();
setInterval(loadHistory, 3000);
setInterval(loadRecordings, 5000);
</script>
</div>
</body>
</html>
