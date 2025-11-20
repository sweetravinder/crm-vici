<?php

class AgentAPI {

    protected $server;
    protected $user;
    protected $pass;
    protected $log;
    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;

        // Load Vicidial API settings
        $stmt = $pdo->query("SELECT * FROM crm_settings_vicidial LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->server = rtrim($row['agent_api_url'], '/');
        $this->user   = $row['api_user'];
        $this->pass   = $row['api_pass'];

        $this->log = ($row['api_logging'] == 1);
    }

    // Main function to send API request
    public function call($function, $params = []) {

        // Add defaults
        $params['function'] = $function;
        $params['source']   = "vicicrm";

        // Build full URL
        $url = $this->server . "/agc/api.php?" . http_build_query($params);

        // Execute
        $response = file_get_contents($url);
        $json = $this->parseResponse($response);

        // Log full request/response
        if ($this->log) {
            $this->logApiCall('AGENT', $function, $params, $response);
        }

        return $json;
    }

    // Convert VICIdial response into usable PHP array
    protected function parseResponse($response) {

        if (!$response) {
            return [
                'result' => 'ERROR',
                'reason' => 'No response from server'
            ];
        }

        // VICIdial sometimes returns pipe-separated text
        if (strpos($response, '|') !== false) {
            $parts = explode('|', trim($response));
            return [
                'result' => $parts[0] ?? 'ERROR',
                'data'   => $parts
            ];
        }

        // JSON?
        $decoded = json_decode($response, true);
        if ($decoded !== null) {
            return $decoded;
        }

        return [
            'result' => 'UNKNOWN',
            'raw' => $response
        ];
    }

    // Log API requests to DB
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
