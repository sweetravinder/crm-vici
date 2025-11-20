<?php
require "../../config.php";
require "../../lib/auth/Auth.php";

Auth::start();
if (!Auth::isLoggedIn()) { header("Location: ../../login.php"); exit; }

?>

<?php include "../../header.php"; ?>
<?php include "../../sidebar.php"; ?>

<div class="content">
<h2>Import Leads (CSV)</h2>

<form method="POST" enctype="multipart/form-data" action="import_process.php"
      style="background:#fff;padding:20px;border:1px solid #ccc;">

Upload CSV File:<br>
<input type="file" name="csv" required><br><br>

List ID:<br>
<input type="text" name="list_id" required><br><br>

<button style="padding:10px 20px;">Upload & Import</button>

</form>

</div>

</div>
</body>
</html>
