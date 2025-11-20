<?php
require_once "config.php";
?>
<html>
<head>
<link rel="stylesheet" href="assets/themes/highcontrast/style.css">
<title><?= $settings['company_name'] ?? 'ViciCRM' ?></title>
</head>
<body>
<header style='padding:10px;background:#111;color:#fff;font-size:20px;'>
<?= $settings['company_name'] ?? 'ViciCRM' ?>
</header>
