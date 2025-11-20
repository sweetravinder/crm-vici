<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../lib/auth/Auth.php';
require_once __DIR__ . '/../../lib/utils/Settings.php';

Auth::requireAdmin();

$settings = Settings::all();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $save = [
        'vici_db_host' => $_POST['vici_db_host'] ?? '',
        'vici_db_name' => $_POST['vici_db_name'] ?? '',
        'vici_db_user' => $_POST['vici_db_user'] ?? '',
        'vici_db_pass' => $_POST['vici_db_pass'] ?? '',
        'vici_agent_api' => $_POST['vici_agent_api'] ?? '',
        'vici_non_agent_api' => $_POST['vici_non_agent_api'] ?? '',
        'vici_recording_url' => $_POST['vici_recording_url'] ?? '',
        'popup_display_mode' => $_POST['popup_display_mode'] ?? 'auto',
        'ami_host' => $_POST['ami_host'] ?? '',
        'ami_port' => $_POST['ami_port'] ?? '',
        'ami_user' => $_POST['ami_user'] ?? '',
        'ami_pass' => $_POST['ami_pass'] ?? '',
    ];
    Settings::save($save);
    header("Location: settings_vicidial.php?saved=1");
    exit;
}

include __DIR__ . '/../../header.php';
include __DIR__ . '/../../sidebar.php';
?>
<div class="content" style="margin-left:220px;padding:20px;">
<h2>Vicidial Integration Settings</h2>

<?php if(isset($_GET['saved'])): ?>
<div style="background:#0b0;color:#000;padding:8px;margin-bottom:10px;">Settings saved.</div>
<?php endif; ?>

<form method="post" style="max-width:900px;">
<h3>Vicidial Database</h3>
<label>Host</label><br>
<input type="text" name="vici_db_host" value="<?= htmlspecialchars($settings['vici_db_host'] ?? '') ?>" style="width:100%"><br>
<label>DB Name</label><br>
<input type="text" name="vici_db_name" value="<?= htmlspecialchars($settings['vici_db_name'] ?? '') ?>" style="width:100%"><br>
<label>User</label><br>
<input type="text" name="vici_db_user" value="<?= htmlspecialchars($settings['vici_db_user'] ?? '') ?>" style="width:100%"><br>
<label>Password</label><br>
<input type="password" name="vici_db_pass" value="<?= htmlspecialchars($settings['vici_db_pass'] ?? '') ?>" style="width:100%"><br>

<h3>APIs & Recordings</h3>
<label>Agent API URL</label><br>
<input type="text" name="vici_agent_api" value="<?= htmlspecialchars($settings['vici_agent_api'] ?? '') ?>" style="width:100%"><br>
<label>Non-Agent API URL</label><br>
<input type="text" name="vici_non_agent_api" value="<?= htmlspecialchars($settings['vici_non_agent_api'] ?? '') ?>" style="width:100%"><br>
<label>Recording Base URL</label><br>
<input type="text" name="vici_recording_url" value="<?= htmlspecialchars($settings['vici_recording_url'] ?? '') ?>" style="width:100%"><br>

<h3>Popup Mode</h3>
<select name="popup_display_mode">
  <option value="window" <?= (isset($settings['popup_display_mode']) && $settings['popup_display_mode']=='window')?'selected':'' ?>>Window</option>
  <option value="modal" <?= (isset($settings['popup_display_mode']) && $settings['popup_display_mode']=='modal')?'selected':'' ?>>Modal</option>
  <option value="slide" <?= (isset($settings['popup_display_mode']) && $settings['popup_display_mode']=='slide')?'selected':'' ?>>Slide-in</option>
  <option value="auto" <?= (isset($settings['popup_display_mode']) && $settings['popup_display_mode']=='auto')?'selected':'' ?>>Auto</option>
</select>

<h3>AMI (optional)</h3>
<label>AMI Host</label><br>
<input type="text" name="ami_host" value="<?= htmlspecialchars($settings['ami_host'] ?? '') ?>" style="width:100%"><br>
<label>AMI Port</label><br>
<input type="text" name="ami_port" value="<?= htmlspecialchars($settings['ami_port'] ?? '') ?>" style="width:100%"><br>
<label>AMI User</label><br>
<input type="text" name="ami_user" value="<?= htmlspecialchars($settings['ami_user'] ?? '') ?>" style="width:100%"><br>
<label>AMI Pass</label><br>
<input type="password" name="ami_pass" value="<?= htmlspecialchars($settings['ami_pass'] ?? '') ?>" style="width:100%"><br>

<br><button type="submit">Save Settings</button>
</form>
</div>
<?php include __DIR__ . '/../../footer.php'; ?>