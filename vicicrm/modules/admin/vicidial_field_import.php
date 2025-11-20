<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../lib/auth/Auth.php';
Auth::requireAdmin();

$vdb = VicidialDB::connect();
if (!$vdb) {
    die("Cannot connect to vicidial DB. Check settings.");
}
$cols = $vdb->query("SHOW COLUMNS FROM vicidial_list")->fetchAll(PDO::FETCH_COLUMN);
$ins = db()->prepare("INSERT INTO crm_vicidial_field_map (vicidial_field,label,field_group) VALUES (?,?,?)");
foreach ($cols as $c) {
    $exists = db()->prepare("SELECT id FROM crm_vicidial_field_map WHERE vicidial_field=?");
    $exists->execute([$c]);
    if ($exists->fetch()) continue;
    $ins->execute([$c, ucwords(str_replace('_',' ',$c)), 'Vicidial']);
}
header('Location: vicidial_field_map.php?imported=1');
exit;
?>