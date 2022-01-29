<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

$checker = new ApiChecker();
$checker->check();

session_start();
session_unset();
session_destroy();

echo '{ "pass": true }';

?>
