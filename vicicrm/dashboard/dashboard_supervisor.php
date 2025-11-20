<?php
require "config.php";
require "lib/auth/Auth.php";
Auth::start();
include "header.php";
include "sidebar.php";
?>
<div class="content">
<h2>Supervisor Live Wallboard</h2>
<div id="wallboard">Loading...</div>
<script>
function loadWB() {
    fetch("ajax/wallboard.php")
    .then(r => r.text())
    .then(html => document.getElementById("wallboard").innerHTML = html);
}
loadWB();
setInterval(loadWB, 2000);
</script>
</div>
</body>
</html>
