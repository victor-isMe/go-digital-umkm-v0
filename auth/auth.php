<?php

if (isset($_SESSION["last_activity"])) {
    $inactive_time = time() - $_SESSION["last_activity"];

    if ($inactive_time > 300) {
        session_unset();
        session_destroy();

        header("Location: index.php?page=login&expired=1");
        exit;
    }
}

$_SESSION["last_activity"] = time();