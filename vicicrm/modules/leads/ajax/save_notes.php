<?php
require "../../../config.php";
require "../../../lib/auth/Auth.php";

Auth::start();
$user = Auth::user($pdo);

$lead_id = $_POST['lead_id'];
$note    = $_POST['note'];

$stmt = $pdo->prepare("INSERT INTO crm_notes (lead_id, agent_user, note) VALUES (?,?,?)");
$stmt->execute([$lead_id, $user['username'], $note]);

echo "Note saved.";
