<?php

class NonAgentAPI {

    protected $server;
    protected $user;
    protected $pass;
    protected $log;
    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;

        // Load settings
        $stmt = $pdo->query("SELECT * FROM crm_settings_vicidial LIMIT 1");
        $row  = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->server = rtrim($row['non_agent_api_url'], '/');
        $this->user   = $row['api_user'];
        $this->pass   = $row['api_pass'];
        $this->log    = ($row['api_logging'] == 1);
    }

    // Main function to call the Non-Agent API
    public function call($function, $params = []) {

        // Required authentication
        $params['source']      = "vicicrm";
        $params['user']        = $this->user;
        $params['pass']        = $this->pass;
        $params['function']    = $function;
        $params['format']      = 'json';

        // Build URL
        $url = $this->server . "/vicidial/non_agent_api.php?" . http_build_query($params);

        // Make Request
        $response = file_get_contents($url);
        $decoded  = $this->parseResponse($response);

        // Logging
        if ($this->log) {
            $this->logApiCall('NON-AGENT', $function, $params, $response);
        }

        return $decoded;
    }

    // Converts VICIdial Non-Agent API responses to structured arrays
    protected function parseResponse($response) {

        if (!$response) {
            return [
                'result' => 'ERROR',
                'reason' => 'No response from Non-Agent API'
            ];
        }

        // VICIdial often returns JSON here
        $json = json_decode($response, true);
        if ($json !== null) {
            return $json;
        }

        // Else return raw
        return [
            'result' => 'UNKNOWN',
            'raw'    => $response
        ];
    }

    // Logs every request & response
    protected function logApiCall($type, $function, $params, $response) {

        $sql = "INSERT INTO crm_api_logs 
                (api_type, api_function, params_json, response_raw)
                VALUES (:t, :f, :p, :r)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            't' => $type,
            'f' => $function,
            'p' => json_encode($params),
            'r' => $response
        ]);
    }
}
