<?php

class ViciHelpers {

    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /* -------------------------------------------------------------
     * SANITIZE VICIDIAL API RESPONSE
     * ------------------------------------------------------------- */
    public function sanitize($value) {
        if (!isset($value)) return "";
        if ($value === null) return "";
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    /* -------------------------------------------------------------
     * GET CAMPAIGN NAME
     * ------------------------------------------------------------- */
    public function getCampaignName($campaign_id) {
        $sql = "SELECT campaign_name FROM vicidial_campaigns WHERE campaign_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$campaign_id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return $r['campaign_name'] ?? $campaign_id;
    }

    /* -------------------------------------------------------------
     * GET LIST NAME
     * ------------------------------------------------------------- */
    public function getListName($list_id) {
        $sql = "SELECT list_name FROM vicidial_lists WHERE list_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$list_id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return $r['list_name'] ?? $list_id;
    }

    /* -------------------------------------------------------------
     * FORMAT CALL TIME (seconds → mm:ss)
     * ------------------------------------------------------------- */
    public function formatCallTime($sec) {
        if (!is_numeric($sec)) return "00:00";
        $m = str_pad(floor($sec / 60), 2, "0", STR_PAD_LEFT);
        $s = str_pad($sec % 60, 2, "0", STR_PAD_LEFT);
        return "$m:$s";
    }

    /* -------------------------------------------------------------
     * CHECK IF AGENT IS LOGGED INTO VICIDIAL
     * ------------------------------------------------------------- */
    public function isAgentLoggedIn($user) {
        $sql = "SELECT * FROM vicidial_live_agents WHERE user = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    /* -------------------------------------------------------------
     * GET ACTIVE LEAD FIELDS FOR POPUP MAPPING
     * ------------------------------------------------------------- */
    public function mapLeadFields($lead) {

        $map = [];

        foreach ($lead as $k => $v) {
            $map[$k] = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
        }

        return $map;
    }

    /* -------------------------------------------------------------
     * API HELPER TO BUILD FULL URL
     * ------------------------------------------------------------- */
    public function buildUrl($base, $params) {
        return $base . "?" . http_build_query($params);
    }

    /* -------------------------------------------------------------
     * RETURN JSON RESPONSE FOR AJAX
     * ------------------------------------------------------------- */
    public static function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

}
