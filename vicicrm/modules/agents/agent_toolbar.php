<div id="agent_toolbar" style="
    background:#333;
    color:white;
    padding:10px;
    display:flex;
    gap:10px;
">
    <button onclick="agentAction('pause')" class="btn btn-warning">Pause</button>
    <button onclick="agentAction('resume')" class="btn btn-success">Resume</button>
    <button onclick="agentAction('hangup')" class="btn btn-danger">Hangup</button>
    <button onclick="agentAction('manual_dial')" class="btn btn-primary">Dial</button>
    <button onclick="openDispo()" class="btn btn-info">Disposition</button>
</div>

<script>
function agentAction(a) {
    $.post("agent_actions.php", {action:a}, function(res){
        console.log(res);
    });
}
</script>
