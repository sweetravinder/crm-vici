<?php
require_once "config.php";
session_start();

$pdo = db();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role_id'], [1,2,3])) {
    die("ACCESS DENIED");
}

$user_id  = $_SESSION['user_id'];
$vic_user = $_SESSION['vicidial_user'];

// Filters
$search = $_GET['search'] ?? "";
$status = $_GET['status'] ?? "";
$page   = max(1, intval($_GET['page'] ?? 1));
$limit  = 20;
$offset = ($page - 1) * $limit;

// WHERE conditions
$where = " WHERE owner_user = :uid ";
$params = ["uid" => $user_id];

if ($search != "") {
    $where .= " AND (phone_number LIKE :s OR first_name LIKE :s2 OR last_name LIKE :s3) ";
    $params["s"] = "%$search%";
    $params["s2"] = "%$search%";
    $params["s3"] = "%$search%";
}

if ($status != "") {
    $where .= " AND crm_status = :status ";
    $params["status"] = $status;
}

// Count total
$count_sql = "SELECT COUNT(*) FROM crm_leads $where";
$stmt = $pdo->prepare($count_sql);
$stmt->execute($params);
$total = $stmt->fetchColumn();

// Fetch leads
$sql = "
    SELECT *
    FROM crm_leads
    $where
    ORDER BY updated_at DESC
    LIMIT $limit OFFSET $offset
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch summary stats
$today = date("Y-m-d");

$kpi = [];

// Leads assigned to me
$kpi['assigned'] = $pdo->prepare("SELECT COUNT(*) FROM crm_leads WHERE owner_user = ?");
$kpi['assigned']->execute([$user_id]);
$kpi['assigned'] = $kpi['assigned']->fetchColumn();

// Pending
$kpi['pending'] = $pdo->prepare("SELECT COUNT(*) FROM crm_leads WHERE owner_user = ? AND crm_status='PENDING'");
$kpi['pending']->execute([$user_id]);
$kpi['pending'] = $kpi['pending']->fetchColumn();

// Today's callbacks
$kpi['callback'] = $pdo->prepare("SELECT COUNT(*) FROM crm_leads WHERE owner_user = ? AND callback_date = ?");
$kpi['callback']->execute([$user_id, $today]);
$kpi['callback'] = $kpi['callback']->fetchColumn();

?>
<!DOCTYPE html>
<html>
<head>
<title>My Leads</title>
<link rel="stylesheet" href="assets/style.css">
<link rel="stylesheet" href="assets/dashboard.css">

<style>
.lead-row:hover {
    background: #f9f9f9;
    cursor: pointer;
}
</style>

<script>
function openLead(lead_id) {
    window.open("popup.php?lead_id=" + lead_id, "leadPopup", "width=450,height=700");
}
</script>

</head>

<body>
<?php include "header.php"; ?>
<?php include "sidebar.php"; ?>

<div class="content">

<h2>My Leads</h2>

<!-- KPIs -->
<div class="kpi-row">
    <div class="kpi-card kpi-blue">
        <div class="kpi-value"><?= $kpi['assigned'] ?></div>
        <div class="kpi-label">Total Assigned</div>
    </div>

    <div class="kpi-card kpi-orange">
        <div class="kpi-value"><?= $kpi['pending'] ?></div>
        <div class="kpi-label">Pending</div>
    </div>

    <div class="kpi-card kpi-green">
        <div class="kpi-value"><?= $kpi['callback'] ?></div>
        <div class="kpi-label">Callbacks Today</div>
    </div>
</div>


<!-- Search Filters -->
<div class="card dashboard-box">
<form method="get">

<input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search name/phone..." style="width:250px;">

<select name="status">
    <option value="">All Status</option>
    <option value="NEW"     <?= $status=='NEW' ? 'selected':'' ?>>New</option>
    <option value="PENDING" <?= $status=='PENDING' ? 'selected':'' ?>>Pending</option>
    <option value="CALLBACK"<?= $status=='CALLBACK' ? 'selected':'' ?>>Callback</option>
    <option value="SALE"    <?= $status=='SALE' ? 'selected':'' ?>>Sale</option>
</select>

<button class="btn btn-primary">Search</button>

</form>
</div>


<!-- Leads Table -->
<div class="card dashboard-box">
<h3>Lead List</h3>

<table class="table">
<tr>
    <th>Lead ID</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Status</th>
    <th>Callback</th>
    <th>Updated</th>
</tr>

<?php foreach ($rows as $r): ?>
<tr class="lead-row" onclick="openLead(<?= $r['lead_id'] ?>)">
    <td><?= $r['lead_id'] ?></td>
    <td><?= $r['first_name'] . ' ' . $r['last_name'] ?></td>
    <td><?= $r['phone_number'] ?></td>
    <td><?= $r['crm_status'] ?></td>
    <td><?= $r['callback_date'] ?: '-' ?></td>
    <td><?= $r['updated_at'] ?></td>
</tr>
<?php endforeach; ?>

</table>

</div>


<!-- Pagination -->
<div class="card dashboard-box">
<?php
$total_pages = ceil($total / $limit);

for ($i=1;$i<=$total_pages;$i++):
    if ($i == $page) {
        echo "<b>$i</b> ";
    } else {
        echo "<a href='?page=$i&search=$search&status=$status'>$i</a> ";
    }
endfor;
?>
</div>

</div><!-- content -->

</body>
</html>
