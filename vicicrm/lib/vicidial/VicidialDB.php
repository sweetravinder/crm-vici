<?php
class VicidialDB {

    public static function connect() {
        $s = Settings::all();
        try {
            return new PDO(
                "mysql:host={$s['vici_db_host']};dbname={$s['vici_db_name']};charset=utf8mb4",
                $s['vici_db_user'],
                $s['vici_db_pass'],
                [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]
            );
        } catch (Exception $e) {
            return false;
        }
    }

}
?>