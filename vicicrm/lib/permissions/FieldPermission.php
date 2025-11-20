<?php
class FieldPermission {

    private $pdo;
    private $user;
    private $role_id;
    private $user_id;

    public function __construct($pdo, $user) {
        $this->pdo = $pdo;
        $this->user = $user;
        $this->role_id = $user['role_id'];
        $this->user_id = $user['user_id'];
    }

    private function getRoleFieldPerm($field_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM crm_role_field_permissions 
                                     WHERE field_id=? AND role_id=? LIMIT 1");
        $stmt->execute([$field_id, $this->role_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function getUserOverride($field_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM crm_user_field_overrides 
                                     WHERE field_id=? AND user_id=? LIMIT 1");
        $stmt->execute([$field_id, $this->user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function finalValue($roleVal, $override) {
        return ($override !== null ? $override : $roleVal);
    }

    public function isVisible($field_id) {
        $role = $this->getRoleFieldPerm($field_id);
        $override = $this->getUserOverride($field_id);
        return $this->finalValue($role['visible'], $override['visible'] ?? null);
    }

    public function isEditable($field_id) {
        $role = $this->getRoleFieldPerm($field_id);
        $override = $this->getUserOverride($field_id);
        return $this->finalValue($role['editable'], $override['editable'] ?? null);
    }

    public function isMandatory($field_id) {
        $role = $this->getRoleFieldPerm($field_id);
        $override = $this->getUserOverride($field_id);
        return $this->finalValue($role['mandatory'], $override['mandatory'] ?? null);
    }
}
