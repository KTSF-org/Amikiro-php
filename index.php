<?php

/**
 * CONTROLEUR PRINCIPAL
 */

session_start();

use app\Setup as app;

require __DIR__ . "/app/autoload.php";

app::run();

?>