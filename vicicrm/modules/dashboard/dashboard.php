<?php
require_once "header.php";
require_once "sidebar.php";
?>

<div class="content">
    <h2>Dashboard</h2>
    <div style="background:#fff;padding:20px;border-radius:4px;border:1px solid #ccc;">

        <h3>Welcome, <?=htmlspecialchars($user['full_name'])?></h3>
        <p>Your role: <?=htmlspecialchars($user['role_id'])?></p>

        <hr>

        <h3>Quick Stats</h3>

        <div style="display:flex;gap:20px;">
            <div style="flex:1;background:#3498db;color:#fff;padding:20px;border-radius:6px;">
                <h2>Total Leads</h2>
                <?php
                $totalLeads = $pdo->query("SELECT COUNT(*) FROM vicidial_list")->fetchColumn();
                echo "<h1>$totalLeads</h1>";
                ?>
            </div>

            <div style="flex:1;background:#e67e22;color:#fff;padding:20px;border-radius:6px;">
                <h2>Today's Calls</h2>
                <?php
                $today = date('Y-m-d');
                $calls = $pdo->query("SELECT COUNT(*) FROM vicidial_log WHERE call_date LIKE '$today%'")
                              ->fetchColumn();
                echo "<h1>$calls</h1>";
                ?>
            </div>

            <div style="flex:1;background:#27ae60;color:#fff;padding:20px;border-radius:6px;">
                <h2>Agents Online</h2>
                <?php
                $agents = $pdo->query("SELECT COUNT(*) FROM live_agents")->fetchColumn();
                echo "<h1>$agents</h1>";
                ?>
            </div>
        </div>

    </div>
</div>

</div> <!-- container end -->
</body>
</html>
