<?php
class Auth {
    static function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit;
        }
    }
    static function requireAdmin() {
        self::requireLogin();
        if ($_SESSION['role']!='admin') {
            die("Access denied");
        }
    }
}
?>