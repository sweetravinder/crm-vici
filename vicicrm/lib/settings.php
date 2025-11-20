<?php

function getSetting($key, $pdo) {
    $stmt = $pdo->prepare("SELECT setting_value FROM crm_system_settings WHERE setting_key=? LIMIT 1");
    $stmt->execute([$key]);
    return $stmt->fetchColumn();
}

function setSetting($key, $value, $pdo) {
    $stmt = $pdo->prepare("
        INSERT INTO crm_system_settings (setting_key, setting_value)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)
    ");
    $stmt->execute([$key, $value]);
}
