<?php
class Settings {
    static function all() {
        $pdo = db();
        $rows = $pdo->query("SELECT setting_key, setting_value FROM crm_vicidial_settings")
                    ->fetchAll(PDO::FETCH_KEY_PAIR);
        return $rows ?: [];
    }

    static function save($data) {
        $pdo = db();
        foreach ($data as $k=>$v) {
            $stmt=$pdo->prepare("INSERT INTO crm_vicidial_settings(setting_key,setting_value)
            VALUES(?,?) ON DUPLICATE KEY UPDATE setting_value=?");
            $stmt->execute([$k,$v,$v]);
        }
    }
}
?>