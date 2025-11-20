<?php
// /vicicrm/sidebar.php
// Central sidebar used by all modules. Uses permission helpers.

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lib/permissions/helpers.php';

if (!isset($_SESSION)) session_start();

$pdo = db();

// small utility to render a section header
function sidebarSection($title) {
    echo "<div class='sidebar-section'><div class='sidebar-section-title'>" . htmlspecialchars($title) . "</div></div>\n";
}

?>
<style>
/* minimal sidebar styles (your site probably has its own styles) */
.sidebar { width:220px; background:#fafafa; padding:10px; border-right:1px solid #eee; float:left; height:100vh; box-sizing:border-box; }
.sidebar-item { padding:8px 6px; margin-bottom:4px; }
.sidebar-item a { color:#333; text-decoration:none; display:block;}
.sidebar-section-title { font-size:12px; color:#666; margin-top:10px; margin-bottom:6px; font-weight:600; }
</style>

<div class="sidebar">

<?php
// Quick profile area
if (isset($_SESSION['user_id'])) {
    $uStmt = $pdo->prepare("SELECT full_name, email FROM crm_users WHERE user_id = ?");
    $uStmt->execute([$_SESSION['user_id']]);
    $u = $uStmt->fetch(PDO::FETCH_ASSOC);
    $displayName = $u['full_name'] ?? 'User';
    echo "<div style='padding:8px;border-bottom:1px solid #eee;margin-bottom:8px;'><strong>" . htmlspecialchars($displayName) . "</strong><br><small>" . htmlspecialchars($u['email'] ?? '') . "</small></div>";
}

// DASHBOARDS
sidebarSection('Dashboards');
showMenu("dashboard_admin", "Admin Dashboard", "modules/dashboard/dashboard_admin.php", $pdo);
showMenu("dashboard_supervisor", "Supervisor Dashboard", "modules/dashboard/dashboard_supervisor.php", $pdo);
showMenu("dashboard_agent", "Agent Dashboard", "modules/dashboard/dashboard_agent.php", $pdo);

// LEADS
sidebarSection('Leads');
showMenu("my_leads", "My Leads", "modules/leads/my_leads.php", $pdo);
showMenu("leads_list", "All Leads", "modules/leads/leads_list.php", $pdo);
showMenu("lead_add", "Add Lead", "modules/leads/lead_add.php", $pdo);

// AGENT TOOLS
sidebarSection('Agent Tools');
showMenu("manual_dial", "Manual Dial", "modules/agent/manual_dial.php", $pdo);
showMenu("dialer_login", "Dialer Login", "modules/agent/dialer_login.php", $pdo);
showMenu("calls_list", "Calls List", "calls_list.php", $pdo); // keep calls_list in root ajax or move later

// SUPERVISOR
sidebarSection('Supervisor');
showMenu("live_agents", "Live Agents", "modules/supervisor/live_agents.php", $pdo);
showMenu("monitor_actions", "Monitor / Whisper / Barge", "modules/supervisor/monitor_actions.php", $pdo);

// RECORDINGS & REPORTS
sidebarSection('Reports');
showMenu("recordings_list", "Recordings", "modules/reports/recordings_list.php", $pdo);
showMenu("agent_report", "Agent Report", "modules/reports/agent_report.php", $pdo);
showMenu("campaign_summary", "Campaign Summary", "modules/reports/campaign_summary.php", $pdo);

// ROLES & SETTINGS
sidebarSection('Admin Tools');
showMenu("roles_list", "Roles & Permissions", "modules/roles/roles_list.php", $pdo);
showMenu("permissions", "Permissions Matrix", "modules/roles/page_permission_matrix.php?role_id=1", $pdo); // link to role 1 by default
showMenu("settings_system", "System Settings", "modules/settings/settings_system.php", $pdo);
showMenu("settings_vicidial", "Dialer Settings", "modules/settings/settings_vicidial.php", $pdo);
showMenu("settings_themes", "Theme Settings", "modules/settings/settings_themes.php", $pdo);

// QUICK LINKS (everyone)
sidebarSection('Quick Links');
echo "<div class='sidebar-item'><a href='popup.php'>Open Popup</a></div>";
echo "<div class='sidebar-item'><a href='logout.php'>Logout</a></div>";

?>

</div> <!-- sidebar -->
