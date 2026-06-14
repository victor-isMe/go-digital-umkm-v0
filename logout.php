<?php
session_start();

require_once "config/database.php";

session_unset();
session_destroy();

setcookie("last_activity", "", time() - 3600, "/");

header("Location: index.php?page=home");
exit;
