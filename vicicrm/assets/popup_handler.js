/* ============================================================
   GLOBAL VARIABLES
============================================================ */
let popupOpen = false;
let popupLeadLoaded = false;

window.currentLeadId = null;
window.currentUniqueid = null;
window.currentPhone = null;
window.currentCampaign = null;

let callTimer = null;
let callSeconds = 0;

/* ============================================================
   TIMER ENGINE
============================================================ */
function startTimer() {
    if (callTimer) clearInterval(callTimer);

    callSeconds = 0;

    callTimer = setInterval(() => {
        callSeconds++;
        let m = String(Math.floor(callSeconds / 60)).padStart(2, "0");
        let s = String(callSeconds % 60).padStart(2, "0");
        document.getElementById("call-timer").innerText = m + ":" + s;
    }, 1000);
}

function stopTimer() {
    if (callTimer) clearInterval(callTimer);
    callSeconds = 0;
    document.getElementById("call-timer").innerText = "00:00";
}

/* ============================================================
   POPUP CONTROL
============================================================ */
function openPopup() {
    if (!popupOpen) {
        popupOpen = true;
        console.log("POPUP OPENED");
    }
}

function closePopup() {
    popupOpen = false;
    popupLeadLoaded = false;

    window.currentLeadId = null;
    window.currentUniqueid = null;

    stopTimer();

    document.getElementById("status-badge").innerText = "WAITING...";
    document.getElementById("callerid").innerText = "Caller: ---";
    document.getElementById("lead-fields-data").innerHTML = "Waiting for next call...";
}

/* ============================================================
   LOAD LEAD DETAILS
============================================================ */
function loadLeadDetails(lead_id, campaign_id) {
    fetch("ajax/popup_api.php?lead_id=" + lead_id + "&campaign_id=" + campaign_id)
        .then(r => r.json())
        .then(json => {
            if (json.status !== "OK") {
                console.error("Popup load error", json);
                return;
            }

            // Build HTML based on dynamic template
            let html = "";
            for (let field of json.template.fields) {
                let key = field.key;
                let label = field.label;
                let value = json.lead[key] ?? "";

                html += `
                    <div style='margin-bottom:8px;'>
                        <strong>${label}:</strong><br>
                        <input type='text'
                               class='input-field'
                               value='${value}'
                               data-field='${key}'
                               onchange='updateLeadField("${key}", this.value)'>
                    </div>
                `;
            }

            document.getElementById("lead-fields-data").innerHTML = html;

            // Set global vars
            window.currentLeadId = lead_id;
            window.currentCampaign = campaign_id;

            popupLeadLoaded = true;

            loadNotes();
            loadRecordings();
        });
}

/* ============================================================
   UPDATE LEAD FIELD (LIVE)
============================================================ */
function updateLeadField(field, value) {
    fetch("ajax/agent_action.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
            function: "update_fields",
            lead_id: window.currentLeadId,
            [field]: value
        })
    });
}

/* ============================================================
   LOAD NOTES
============================================================ */
function loadNotes() {
    if (!window.currentLeadId) return;

    fetch("ajax/load_notes.php?lead_id=" + window.currentLeadId)
        .then(r => r.text())
        .then(html => {
            document.getElementById("notes-container").innerHTML = html;
        });
}

/* ============================================================
   LOAD RECORDINGS
============================================================ */
function loadRecordings() {
    if (!window.currentLeadId) return;

    fetch("ajax/load_recordings.php?lead_id=" + window.currentLeadId)
        .then(r => r.text())
        .then(html => {
            document.getElementById("recordings-container").innerHTML = html;
        });
}

/* ============================================================
   CHECK CALL STATUS (MAIN ENGINE)
============================================================ */
function checkCall() {
    fetch("ajax/check_call.php")
        .then(r => r.json())
        .then(event => {
            if (event.event === "none") return;

            console.log("EVENT:", event);

            /* -------------------------
               RINGING
            --------------------------- */
            if (event.event === "ring") {

                openPopup();

                document.getElementById("status-badge").innerText = "RINGING";
                document.getElementById("callerid").innerText = "Caller: " + event.phone;

                window.currentLeadId = event.lead_id;
                window.currentUniqueid = event.uniqueid;

                popupLeadLoaded = false;

                stopTimer();
            }

            /* -------------------------
               ANSWERED
            --------------------------- */
            if (event.event === "answer") {

                document.getElementById("status-badge").innerText = "INCALL";
                document.getElementById("callerid").innerText = "Caller: " + event.phone;

                window.currentLeadId = event.lead_id;
                window.currentUniqueid = event.uniqueid;

                if (!popupLeadLoaded) {
                    loadLeadDetails(event.lead_id, event.campaign_id);
                }

                startTimer();
            }

            /* -------------------------
               HANGUP (DISPOSITION MODE)
            --------------------------- */
            if (event.event === "hangup") {

                document.getElementById("status-badge").innerText = "DISPO";

                stopTimer();

                // Do not close popup
                // Disposition must be submitted manually
            }
        });
}

/* ============================================================
   MAIN INTERVAL LOOP
============================================================ */
setInterval(checkCall, 1000);
