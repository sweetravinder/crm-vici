<?php

class ViciSync {

    protected $pdo;
    protected $vicidial_db;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;

        // VICIdial DB credentials
        $row = $pdo->query("SELECT * FROM crm_settings_vicidial LIMIT 1")->fetch(PDO::FETCH_ASSOC);

        $this->vicidial_db = [
            'host' => $row['vicidial_db_host'],
            'name' => $row['vicidial_db_name'],
            'user' => $row['vicidial_db_user'],
            'pass' => $row['vicidial_db_pass'],
        ];
    }

    /* -------------------------------------------------------------
     * CONNECT TO VICIDIAL DATABASE
     * ------------------------------------------------------------- */
    protected function connectVici() {
        $dsn = "mysql:host=" . $this->vicidial_db['host'] .
               ";dbname=" . $this->vicidial_db['name'];

        return new PDO($dsn,
                       $this->vicidial_db['user'],
                       $this->vicidial_db['pass'],
                       [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    /* -------------------------------------------------------------
     * GET A VICIDIAL USER BY ID
     * ------------------------------------------------------------- */
    public function getViciUser($user) {
        $db = $this->connectVici();
        $stmt = $db->prepare("SELECT * FROM vicidial_users WHERE user = ?");
        $stmt->execute([$user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* -------------------------------------------------------------
     * SYNC VICIDIAL USER INTO CRM
     * ------------------------------------------------------------- */
    public function syncUserIntoCRM($user) {

        $vici = $this->getViciUser($user);
        if (!$vici) return false;

        $sql = "SELECT * FROM crm_users WHERE vicidial_user = :u LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['u' => $user]);
        $crm = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($crm) {
            // Update CRM entry
            $update = $this->pdo->prepare("
                UPDATE crm_users
                SET full_name = :n,
                    email = :e,
                    role_id = role_id,
                    phone_login = :p,
                    phone_pass = :pp
                WHERE user_id = :id
            ");
            $update->execute([
                'n'  => $vici['full_name'],
                'e'  => $vici['email'],
                'p'  => $vici['phone_login'],
                'pp' => $vici['phone_pass'],
                'id' => $crm['user_id']
            ]);
        } else {
            // Create CRM user
            $insert = $this->pdo->prepare("
                INSERT INTO crm_users (username, full_name, email, active, vicidial_user, phone_login, phone_pass)
                VALUES (:u, :n, :e, 1, :vu, :p, :pp)
            ");
            $insert->execute([
                'u'  => $user,
                'n'  => $vici['full_name'],
                'e'  => $vici['email'],
                'vu' => $user,
                'p'  => $vici['phone_login'],
                'pp' => $vici['phone_pass']
            ]);
        }

        $this->logSync("SYNC_IN", $user, "VICIDIAL → CRM");
        return true;
    }

    /* -------------------------------------------------------------
     * SYNC CRM USER BACK INTO VICIDIAL
     * ------------------------------------------------------------- */
    public function syncUserIntoVici($crmUser) {

        $db = $this->connectVici();

        $update = $db->prepare("
            UPDATE vicidial_users
            SET full_name = :n,
                email = :e,
                pass = :p,
                phone_login = :pl,
                phone_pass = :pp
            WHERE user = :u
        ");

        $update->execute([
            'n'  => $crmUser['full_name'],
            'e'  => $crmUser['email'],
            'p'  => $crmUser['password'],
            'pl' => $crmUser['phone_login'],
            'pp' => $crmUser['phone_pass'],
            'u'  => $crmUser['vicidial_user'],
        ]);

        $this->logSync("SYNC_OUT", $crmUser['vicidial_user'], "CRM → VICIDIAL");
    }

    /* -------------------------------------------------------------
     * LOG SYNC ACTIONS
     * ------------------------------------------------------------- */
    protected function logSync($type, $user, $msg) {

        $sql = "INSERT INTO crm_sync_logs 
                (sync_type, user, message)
                VALUES (:t, :u, :m)";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            't' => $type,
            'u' => $user,
            'm' => $msg
        ]);
    }

}
