<?php

class Realtime {

    protected $pdo;
    protected $server_ip;
    protected $agent_api_url;
    protected $debug_logs = true;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;

        // Load VICIdial settings
        $stmt = $pdo->query("SELECT * FROM crm_settings_vicidial LIMIT 1");
        $row  = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->server_ip     = $row['vicidial_server_ip'];
        $this->agent_api_url = rtrim($row['agent_api_url'], '/');
        $this->debug_logs    = ($row['api_logging'] == 1);
    }

    /* -------------------------------------------------------------
     * REAL-TIME: GET LIVE AGENT CALL DATA
     * ------------------------------------------------------------- */
    public function getAgentLiveCall($user) {

        $url = $this->agent_api_url .
               "/agc/api.php?function=agent_status&user=" . urlencode($user);

        $raw = file_get_contents($url);

        if ($this->debug_logs) {
            $this->log("agent_status", $user, $raw);
        }

        return $this->parseResponse($raw);
    }

    /* -------------------------------------------------------------
     * REAL-TIME: GET LIVE CALLS (supervisor)
     * ------------------------------------------------------------- */
    public function getLiveCalls() {
        $sql = "SELECT * FROM vicidial_auto_calls ORDER BY call_time DESC LIMIT 200";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -------------------------------------------------------------
     * REAL-TIME: GET LIVE AGENTS (supervisor)
     * ------------------------------------------------------------- */
    public function getLiveAgents() {
        $sql = "SELECT * 
                FROM vicidial_live_agents 
                ORDER BY last_update_time DESC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -------------------------------------------------------------
     * REAL-TIME: DETECT POPUP EVENTS FOR AGENT (ring/answer/hangup)
     * ------------------------------------------------------------- */
    public function detectPopupEvent($user) {

        $data = $this->getAgentLiveCall($user);

        if (!is_array($data)) {
            return ['event' => 'none'];
        }

        $status = $data['status'] ?? null;
        $lead_id = $data['lead_id'] ?? null;

        /* EVENT: RINGING */
        if ($status == "RING" || $status == "RINGING") {
            return [
                'event'       => 'ring',
                'lead_id'     => $lead_id,
                'phone'       => $data['phone_number'] ?? '',
                'campaign_id' => $data['campaign_id'] ?? '',
                'uniqueid'    => $data['uniqueid'] ?? '',
            ];
        }

        /* EVENT: INCALL */
        if ($status == "INCALL") {
            return [
                'event'       => 'answer',
                'lead_id'     => $lead_id,
                'phone'       => $data['phone_number'] ?? '',
                'campaign_id' => $data['campaign_id'] ?? '',
                'uniqueid'    => $data['uniqueid'] ?? '',
                'timer'       => $data['talk_time'] ?? 0,
            ];
        }

        /* EVENT: HANGUP */
        if ($status == "READY" || $status == "PAUSED" || $status == "DISPO") {
            return [
                'event' => 'hangup',
                'lead_id' => $lead_id,
            ];
        }

        return ['event' => 'none'];
    }

    /* -------------------------------------------------------------
     * RESPONSE PARSER
     * ------------------------------------------------------------- */
    protected function parseResponse($response) {

        if (!$response) {
            return ['result' => 'ERROR', 'reason' => 'No response from server'];
        }

        // JSON?
        $json = json_decode($response, true);
        if ($json !== null) {
            return $json;
        }

        // Pipe-delimited fallback
        if (strpos($response, '|') !== false) {
            return explode('|', $response);
        }

        return ['raw' => $response];
    }

    /* -------------------------------------------------------------
     * LOGGING REALTIME EVENTS
     * ------------------------------------------------------------- */
    protected function log($function, $user, $response) {

        $sql = "INSERT INTO crm_api_logs
                (api_type, api_function, params_json, response_raw)
                VALUES ('REALTIME', :f, :p, :r)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'f' => $function,
            'p' => json_encode(['user' => $user]),
            'r' => $response
        ]);
    }

}
