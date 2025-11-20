<?php
require_once "../../config.php";
require_once "../../lib/permissions/helpers.php";
session_start();

$pdo = db();

if (!can_edit("agent_edit", $pdo)) {
    die("ACCESS DENIED");
}

$uid = $_POST["user_id"];
$full = $_POST["full_name"];
$email = $_POST["email"];
$role  = $_POST["role_id"];
$active = $_POST["active"];
$vd_user = $_POST["vicidial_user"];
$vd_pass = $_POST["vicidial_pass"];

// Update CRM user
$pdo->prepare("
    UPDATE crm_users 
    SET full_name=?, email=?, role_id=?, active=?, vicidial_user=?, vicidial_pass=? 
    WHERE user_id=?
")->execute([$full, $email, $role, $active, $vd_user, $vd_pass, $uid]);

// Password?
if ($_POST['newpass']) {
    $pdo->prepare("UPDATE crm_users SET password=? WHERE user_id=?")
        ->execute([md5($_POST['newpass']), $uid]);
}

// Log
$pdo->prepare("
    INSERT INTO crm_audit_log (user_id, details, created_at)
    VALUES (?, ?, NOW())
")->execute([$uid, "Updated agent profile"]);

// Optional future: sync into vicidial.users
// We leave hook empty here

header("Location: agents_list.php?updated=1");
exit;
