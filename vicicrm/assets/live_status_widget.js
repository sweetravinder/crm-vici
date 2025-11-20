/* ===============================================================
   LIVE STATUS WIDGET — Refreshes every 1 second
=============================================================== */

function updateLiveStatus() {

    fetch("ajax/live_agent_status.php")
        .then(r => r.json())
        .then(json => {
            if (json.status !== "OK") return;

            let d = json.live;

            // Build HTML dynamically
            let html = `
                <div class="kpi-row">

                    <div class="kpi-card kpi-blue">
                        <div class="kpi-value">${d.online_agents}</div>
                        <div class="kpi-label">Agents Online</div>
                    </div>

                    <div class="kpi-card kpi-green">
                        <div class="kpi-value">${d.ready}</div>
                        <div class="kpi-label">Ready</div>
                    </div>

                    <div class="kpi-card kpi-yellow">
                        <div class="kpi-value">${d.paused}</div>
                        <div class="kpi-label">Paused</div>
                    </div>

                    <div class="kpi-card kpi-aqua">
                        <div class="kpi-value">${d.incall}</div>
                        <div class="kpi-label">In Call</div>
                    </div>

                    <div class="kpi-card kpi-red">
                        <div class="kpi-value">${d.queue_waiting}</div>
                        <div class="kpi-label">Waiting</div>
                    </div>

                    <div class="kpi-card kpi-purple">
                        <div class="kpi-value">${d.active_calls}</div>
                        <div class="kpi-label">Active Calls</div>
                    </div>

                </div>
            `;

            document.getElementById("live-status-bar").innerHTML = html;
        });
}

// refresh every second
setInterval(updateLiveStatus, 1000);
updateLiveStatus();
