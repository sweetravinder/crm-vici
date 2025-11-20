<?php
require_once "lib/auth/Auth.php";
Auth::start();
Auth::logout();
header("Location: login.php");
exit;
