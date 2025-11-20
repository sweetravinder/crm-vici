<?php
require "../../../config.php";
$user = $_GET['user'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM vicidial_users WHERE user=?");
$stmt->execute([$user]);
$a = $stmt->fetch();
echo json_encode($a);
