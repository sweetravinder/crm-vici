<?php
require "config.php";
require "lib/auth/Auth.php";
Auth::start();
$user = Auth::user($pdo);
$agent = $user['username'] ?? '';

include "header.php";
include "sidebar.php";
?>
<div class="content">
<h2>Agent Live Dashboard</h2>
<div style="display:flex;gap:20px;flex-wrap:wrap;">
<div class="card"><h3>Status</h3><div id="live_status">...</div></div>
<div class="card"><h3>Calls Today</h3><div id="calls_today">0</div></div>
<div class="card"><h3>Talk Time</h3><div id="talk_time">0 sec</div></div>
<div class="card"><h3>Conversions</h3><div id="conversion">0</div></div>
</div>
<br>
<div class="card">
<h3>Last 10 Calls</h3>
<div id="recent_calls"></div>
</div>

<script>
function loadAgentLive() {
    fetch("ajax/agent_live.php")
    .then(r => r.json())
    .then(d => {
        document.getElementById("live_status").innerHTML  = d.status;
        document.getElementById("calls_today").innerHTML  = d.calls_today;
        document.getElementById("talk_time").innerHTML    = d.talk_time + " sec";
        document.getElementById("conversion").innerHTML   = d.conversions;
        document.getElementById("recent_calls").innerHTML = d.recent_html;
    });
}
loadAgentLive();
setInterval(loadAgentLive, 2000);
</script>
</div>
</body>
</html>
