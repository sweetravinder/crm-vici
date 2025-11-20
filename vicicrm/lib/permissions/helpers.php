<?php
// /vicicrm/lib/permissions/helpers.php
// Utility functions for role/page/field permissions
// Requires config.php -> db() to be available when used.

if (!function_exists('getRolePermissions')) {
    /**
     * Load and cache role permissions into session to reduce DB queries.
     * Returns associative array: [ page_key => ['can_view'=>0/1,'can_edit'=>0/1,'can_delete'=>0/1], ... ]
     */
    function getRolePermissions(PDO $pdo, int $role_id): array
    {
        if (!$role_id) return [];

        // use session cache if available and role_id matches
        if (isset($_SESSION['role_perms']) && isset($_SESSION['role_perms_role']) && $_SESSION['role_perms_role'] == $role_id) {
            return $_SESSION['role_perms'];
        }

        $stmt = $pdo->prepare("SELECT page_key, can_view, can_edit, can_delete FROM crm_role_permissions WHERE role_id = ?");
        $stmt->execute([$role_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $perms = [];
        foreach ($rows as $r) {
            $perms[$r['page_key']] = [
                'can_view' => (int)$r['can_view'],
                'can_edit' => (int)$r['can_edit'],
                'can_delete'=> (int)$r['can_delete'],
            ];
        }

        $_SESSION['role_perms'] = $perms;
        $_SESSION['role_perms_role'] = $role_id;

        return $perms;
    }
}

if (!function_exists('can_view')) {
    /**
     * Returns true if current role_user can view the given page_key.
     * Usage: if (can_view('dashboard_admin', $pdo)) { ... }
     */
    function can_view(string $page_key, PDO $pdo, $role_id = null): bool
    {
        if (!isset($_SESSION)) session_start();

        // Admin (role_id 1) bypass
        $role_id = $role_id ?? ($_SESSION['role_id'] ?? 0);
        if (!$role_id) return false;
        if ($role_id == 1) return true;

        $perms = getRolePermissions($pdo, $role_id);
        if (isset($perms[$page_key])) {
            return (bool)$perms[$page_key]['can_view'];
        }

        // default: deny if not set
        return false;
    }
}

if (!function_exists('can_edit')) {
    function can_edit(string $page_key, PDO $pdo, $role_id = null): bool
    {
        if (!isset($_SESSION)) session_start();
        $role_id = $role_id ?? ($_SESSION['role_id'] ?? 0);
        if (!$role_id) return false;
        if ($role_id == 1) return true;

        $perms = getRolePermissions($pdo, $role_id);
        return isset($perms[$page_key]) ? (bool)$perms[$page_key]['can_edit'] : false;
    }
}

if (!function_exists('can_delete')) {
    function can_delete(string $page_key, PDO $pdo, $role_id = null): bool
    {
        if (!isset($_SESSION)) session_start();
        $role_id = $role_id ?? ($_SESSION['role_id'] ?? 0);
        if (!$role_id) return false;
        if ($role_id == 1) return true;

        $perms = getRolePermissions($pdo, $role_id);
        return isset($perms[$page_key]) ? (bool)$perms[$page_key]['can_delete'] : false;
    }
}

if (!function_exists('showMenu')) {
    /**
     * showMenu()
     * Renders a sidebar menu item if the current role has view permission for page_key.
     *
     * Parameters:
     *  - $page_key: permission key used in crm_role_permissions (e.g. "dashboard_admin")
     *  - $label: visible menu text
     *  - $path: relative path to the page (from root), e.g. "modules/dashboard/dashboard_admin.php"
     *  - $pdo: PDO instance (required)
     *  - $icon: optional HTML or CSS class for menu icon
     */
    function showMenu(string $page_key, string $label, string $path, PDO $pdo, string $icon = ''): void
    {
        // Admin shortcut - always show
        $role_id = $_SESSION['role_id'] ?? 0;
        if ($role_id == 1 || can_view($page_key, $pdo, $role_id)) {
            $active = '';
            // determine active by comparing script name
            $current = basename($_SERVER['SCRIPT_NAME']);
            $linkBase = basename($path);
            if ($current === $linkBase) $active = ' style="font-weight:700;"';

            $iconHtml = $icon ? "<span class='menu-icon'>{$icon}</span> " : '';
            echo "<div class='sidebar-item'><a href='{$path}' {$active}>{$iconHtml}" . htmlspecialchars($label) . "</a></div>\n";
        }
    }
}
