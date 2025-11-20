<?php
class Permission {
    static function can($module) {
        $role = $_SESSION['role'] ?? 'agent';
        if ($role=='admin') return true;
        return true; // placeholder full access
    }
}
?>