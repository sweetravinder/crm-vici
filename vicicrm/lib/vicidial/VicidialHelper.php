<?php
class VicidialHelper {

    public static function getLead($lead_id) {
        $vdb = VicidialDB::connect();
        if(!$vdb) return false;
        $stmt = $vdb->prepare("SELECT * FROM vicidial_list WHERE lead_id=?");
        $stmt->execute([$lead_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAgentStatus($user) {
        $vdb = VicidialDB::connect();
        if(!$vdb) return false;
        $stmt = $vdb->prepare("SELECT * FROM live_agents WHERE user=?");
        $stmt->execute([$user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
?>