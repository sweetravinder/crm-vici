<?php
require "../../config.php";
require "../../lib/auth/Auth.php";
require "../../lib/permissions/helpers.php";

Auth::start();
if (!Auth::isLoggedIn()) { header("Location: ../../login.php"); exit; }

$user = Auth::user($pdo);

if (!can_edit("agents", $pdo, $user)) {
    die("Access Denied");
}

$agent = $_GET['user'];

// Fetch agent
$stmt = $pdo->prepare("SELECT * FROM vicidial_users WHERE user=?");
$stmt->execute([$agent]);
$info = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$info) die("Agent not found");

$msg = "";

// SAVE
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql = "
        UPDATE vicidial_users SET
            full_name = ?,
            user_group = ?,
            phone_login = ?,
            active = ?
    ";

    $params = [
        $_POST['full_name'],
        $_POST['user_group'],
        $_POST['phone_login'],
        $_POST['active']
    ];

    // Password update optional
    if (!empty($_POST['pass'])) {
        $sql .= ", pass = ?";
        $params[] = $_POST['pass'];
    }

    $sql .= " WHERE user=?";
    $params[] = $agent;

    $pdo->prepare($sql)->execute($params);

    $msg = "Agent updated successfully";

    // Refresh
    $stmt->execute([$agent]);
    $info = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>

<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">
<h2>Edit Agent: <?=$agent?></h2>

<?php if ($msg): ?>
<p style="color:green;font-weight:bold;"><?=$msg?></p>
<?php endif; ?>

<form method="POST" style="background:#fff;padding:20px;border:1px solid #ccc;">

Full Name:<br>
<input type="text" name="full_name" value="<?=$info['full_name']?>" style="width:50%;padding:8px;"><br><br>

User Group:<br>
<input type="text" name="user_group" value="<?=$info['user_group']?>" style="width:50%;padding:8px;"><br><br>

Phone Login:<br>
<input type="text" name="phone_login" value="<?=$info['phone_login']?>" style="width:50%;padding:8px;"><br><br>

Password (optional):<br>
<input type="password" name="pass" style="width:50%;padding:8px;"><br><br>

Active:<br>
<select name="active" style="padding:8px;">
    <option value="Y" <?=$info['active']=="Y"?"selected":""?>>Yes</option>
    <option value="N" <?=$info['active']=="N"?"selected":""?>>No</option>
</select>
<br><br>

<button style="padding:10px 20px;">Save Agent</button>

</form>

</div>

</div>
</body>
</html>
