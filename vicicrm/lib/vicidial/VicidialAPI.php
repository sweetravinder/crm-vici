<?php
class VicidialAPI {

    public static function callAPI($url, $params) {
        $full = $url . '?' . http_build_query($params);
        $res = @file_get_contents($full);
        return $res ?: false;
    }

    public static function agentCommand($cmd, $user, $phone, $session, $server_ip, $api_url) {
        $params = [
            'source' => 'vicicrm',
            'user' => $user,
            'pass' => $user,
            'agent_user' => $user,
            'function' => $cmd,
            'phone_login' => $phone,
            'session' => $session,
            'server_ip' => $server_ip
        ];
        return self::callAPI($api_url, $params);
    }

    public static function nonAgent($params, $url) {
        return self::callAPI($url, $params);
    }

}
?>