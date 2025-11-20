/*
  popup_handler.js — Full Popup Engine (Phase 3)
  Handles:
   • popup open/close
   • dynamic field rendering
   • notes add/load
   • history load
   • recordings load
   • agent actions
   • real-time timers
*/

// GLOBAL
let vcPopupMode = 'modal';
let vcCurrentLead = null;
let vcCurrentUniqueID = null;

// OPEN POPUP
function vcOpenPopup(lead_id) {
    vcCurrentLead = lead_id;
    fetch('ajax/popup_api.php?action=lead&lead_id='+lead_id)
    .then(r=>r.json())
    .then(d=>{
        if(d.status !== 'success'){ alert(d.message); return; }
        vcRenderPopup(d.data);
    });
}

// RENDER POPUP DATA
function vcRenderPopup(data){
    const body = document.getElementById('popup_body');
    if(!body){ console.error('popup_body missing'); return; }

    let html = "";

    // AGENT BAR
    html += `<div class="vc-agent-bar">
                <button onclick="vcAgentCmd('pause')">Pause</button>
                <button onclick="vcAgentCmd('resume')">Resume</button>
                <button onclick="vcAgentCmd('hangup')">Hangup</button>
                <button onclick="vcAgentCmd('record')">Record</button>
            </div><hr>`;

    // FIELDS
    html += `<h3>Lead Details</h3>`;
    data.fields.forEach(f=>{
        html += `<div><b>${f.label}:</b> ${f.value}</div>`;
    });

    // NOTES
    html += `<hr><h3>Notes</h3><div id="vc-notes">`;
    data.notes.forEach(n=>{
        html += `<div><b>${n.full_name}</b>: ${n.note_text} <i>${n.created_at}</i></div>`;
    });
    html += `</div>
             <textarea id="vc-note-text"></textarea>
             <button onclick="vcAddNote()">Add Note</button><hr>`;

    // HISTORY
    html += `<h3>Call History</h3><div id="vc-history">`;
    data.history.forEach(h=>{
        html += `<div>Call: ${h.status} — ${h.call_date}</div>`;
    });
    html += `</div>`;

    body.innerHTML = html;
}

// ADD NOTE
function vcAddNote(){
    let t = document.getElementById('vc-note-text').value;
    if(!t.trim()){ alert('Note empty'); return; }

    let fd = new FormData();
    fd.append('lead_id', vcCurrentLead);
    fd.append('text', t);

    fetch('ajax/popup_api.php?action=add_note',{method:'POST',body:fd})
    .then(r=>r.json())
    .then(d=>{
        if(d.status==='success'){ vcOpenPopup(vcCurrentLead); }
    });
}

// AGENT COMMAND
function vcAgentCmd(cmd){
    let fd = new FormData();
    fd.append('cmd',cmd);
    fd.append('session','');   // will integrate later
    fd.append('server_ip',''); // from live_agents
    fd.append('phone','');     // from session

    fetch('ajax/popup_api.php?action=agent_cmd',{method:'POST',body:fd})
    .then(r=>r.json())
    .then(d=>console.log(d));
}
