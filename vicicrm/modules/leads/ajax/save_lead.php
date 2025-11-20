<?php
require "../../../config.php";
require "../../../lib/auth/Auth.php";

Auth::start();

$lead_id = $_POST['lead_id'];

$sql = "UPDATE vicidial_list SET 
        first_name=?, 
        last_name=?, 
        phone_number=?, 
        alt_phone=?, 
        email=?, 
        address1=?, 
        city=?, 
        state=?, 
        postal_code=? 
        WHERE lead_id=?";

$pdo->prepare($sql)->execute([
    $_POST['first_name'],
    $_POST['last_name'],
    $_POST['phone_number'],
    $_POST['alt_phone'],
    $_POST['email'],
    $_POST['address1'],
    $_POST['city'],
    $_POST['state'],
    $_POST['postal_code'],
    $lead_id
]);

echo "Lead updated.";
