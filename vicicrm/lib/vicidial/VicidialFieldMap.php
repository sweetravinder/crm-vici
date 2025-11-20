<?php
class VicidialFieldMap {

    public static function all() {
        $pdo = db();
        return $pdo->query("SELECT * FROM crm_vicidial_field_map ORDER BY sort_order ASC")
                   ->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function popup($role) {
        $pdo = db();
        $sql = "SELECT * FROM crm_vicidial_field_map WHERE show_in_popup=1";
        if($role=='admin')   $sql .= " AND visible_admin=1";
        if($role=='supervisor') $sql .= " AND visible_supervisor=1";
        if($role=='agent')   $sql .= " AND visible_agent=1";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function view($role) {
        $pdo = db();
        $sql = "SELECT * FROM crm_vicidial_field_map WHERE show_in_view=1";
        if($role=='admin')   $sql .= " AND visible_admin=1";
        if($role=='supervisor') $sql .= " AND visible_supervisor=1";
        if($role=='agent')   $sql .= " AND visible_agent=1";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>