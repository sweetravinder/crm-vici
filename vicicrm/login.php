<?php
require_once "config.php";
require_once "lib/auth/Auth.php";

Auth::start();

if (Auth::isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD']=="POST") {
    if (Auth::login($_POST['username'], $_POST['password'], $pdo)) {
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Invalid login credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>VICICRM Login</title>
<style>
body { margin:0; background:#f5f5f5; font-family:Arial; }
.loginbox {
    width:350px; margin:100px auto; background:#fff; padding:25px;
    border:1px solid #ccc; border-radius:6px;
}
input { width:100%; padding:10px; margin-bottom:10px; }
button { width:100%; padding:10px; background:#2c3e50; color:#fff; border:none; }
</style>
</head>
<body>

<div class="loginbox">
    <h2>Login</h2>

    <?php if ($message) echo "<p style='color:red;'>$message</p>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button>Login</button>
    </form>
</div>

</body>
</html>
