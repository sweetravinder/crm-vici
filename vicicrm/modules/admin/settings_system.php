<?php
require "../../config.php";
require "../../lib/auth/Auth.php";

Auth::start();
if (!Auth::isLoggedIn()) { header("Location: ../../login.php"); exit; }

$user = Auth::user($pdo);

// Optional: Add ACL check for admin
if ($user['role'] != 'admin') die("Access Denied");

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    foreach ($_POST as $key => $value) {
        setSetting($key, $value, $pdo);
    }
    $msg = "Settings Updated Successfully!";
}

// Load settings
$fields = $pdo->query("SELECT * FROM crm_system_settings ORDER BY setting_key")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">

<h2>System Settings</h2>

<?php if ($msg): ?>
<div style="background:#d2ffd2;padding:10px;border:1px solid #0f0;"><?=$msg?></div>
<?php endif; ?>

<form method="POST" style="background:#fff;padding:20px;border:1px solid #ccc;">

<table width="100%" cellpadding="8">

<?php foreach ($fields as $f): ?>
<tr>
    <td width="25%"><b><?=$f['setting_key']?></b></td>
    <td>
        <input type="text" name="<?=$f['setting_key']?>" 
               value="<?=htmlspecialchars($f['setting_value'])?>" 
               style="width:100%;padding:8px;">
    </td>
</tr>
<?php endforeach; ?>

</table>

<br>
<button style="padding:10px 20px;">Save Changes</button>

</form>

</div>

</div>
</body>
</html>
