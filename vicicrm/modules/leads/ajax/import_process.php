<?php
require "../../config.php";
require "../../lib/auth/Auth.php";

Auth::start();

$list_id = $_POST['list_id'];

if (!isset($_FILES['csv'])) die("CSV not found");

$fh = fopen($_FILES['csv']['tmp_name'], "r");
$count = 0;

while (($cols = fgetcsv($fh, 10000, ",")) !== false) {

    if (count($cols) < 3) continue; // Minimum fields

    $phone = trim($cols[0]);
    $fname = trim($cols[1]);
    $lname = trim($cols[2]);

    // Duplicate check
    $dup = $pdo->prepare("SELECT lead_id FROM vicidial_list WHERE phone_number=? LIMIT 1");
    $dup->execute([$phone]);
    if ($dup->rowCount()) continue;

    $pdo->prepare("
        INSERT INTO vicidial_list 
            (entry_date,modify_date,list_id,status,phone_number,first_name,last_name)
        VALUES (NOW(),NOW(),?,'NEW',?,?,?)
    ")->execute([$list_id, $phone, $fname, $lname]);

    $count++;
}

echo "Imported $count leads";
