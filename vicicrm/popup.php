<?php
require_once "config.php";
require_once "lib/vicidial/helpers.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "NOT LOGGED IN";
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Live Call Popup</title>
<link rel="stylesheet" href="assets/style.css">

<style>
body {
    background: #f5f5f5;
    margin: 0;
    padding: 10px;
    font-family: Arial;
}

#popup-container {
    width: 100%;
    max-width: 900px;
    margin: auto;
}

#call-header {
    padding: 12px;
    background: #004aad;
    color: white;
    border-radius: 4px;
    margin-bottom: 10px;
}

#status-badge {
    background: #fff;
    color: #004aad;
    padding: 5px 10px;
    border-radius: 4px;
    font-weight: bold;
}

#call-timer {
    font-size: 18px;
    font-weight: bold;
    margin-left: 10px;
}

#lead-fields {
    background: white;
    padding: 12px;
    border-radius: 4px;
    margin-top: 10px;
}

#tabs {
    margin-top: 15px;
}

.tab-btn {
    padding: 8px 12px;
    background: #ddd;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 5px;
}

.tab-btn.active {
    background: #004aad;
    color: white;
}

.tab-panel {
    display: none;
    background: white;
    padding: 12px;
    border-radius: 4px;
    margin-top: 10px;
}
</style>
</head>

<body>

<div id="popup-container">

    <!--============================
        CALL HEADER
    ==============================-->
    <div id="call-header">
        <span id="status-badge">WAITING...</span>
        <span id="call-timer">00:00</span>

        <div style="float:right;">
            <strong id="callerid">Caller: ---</strong>
        </div>

        <div style="clear: both;"></div>
    </div>

    <!--============================
        AGENT ACTION BAR
    ==============================-->
    <div id="agent-actions-container">
        <?php include "modules/agent/agent_actions.php"; ?>
    </div>


    <!--============================
        LEAD FIELDS (Dynamic)
    ==============================-->
    <div id="lead-fields">
        <h3>Customer Details</h3>
        <div id="lead-fields-data">Loading...</div>
    </div>


    <!--============================
        TABS
    ==============================-->
    <div id="tabs">
        <span class="tab-btn active" onclick="openTab('notes')">Notes</span>
        <span class="tab-btn" onclick="openTab('recordings')">Recordings</span>
    </div>

    <!--============================
        NOTES TAB
    ==============================-->
    <div id="notes" class="tab-panel" style="display:block;">
        <div id="notes-container">Loading notes...</div>
    </div>

    <!--============================
        RECORDINGS TAB
    ==============================-->
    <div id="recordings" class="tab-panel">
        <div id="recordings-container">Loading recordings...</div>
    </div>

</div>

<script src="assets/popup_handler.js"></script>
<script>
function openTab(tab) {
    document.querySelectorAll('.tab-panel').forEach(div => div.style.display = "none");
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));

    document.getElementById(tab).style.display = "block";
    event.target.classList.add('active');
}
</script>

</body>
</html>
