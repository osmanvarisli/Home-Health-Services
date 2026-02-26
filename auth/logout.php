<?php
session_start();
$_SESSION = [];
session_destroy();

require_once "config/init.php"; // BASE_URL tanımlı
header("Location: " . BASE_URL . "login");
exit;

exit;
