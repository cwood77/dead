<?php

require '/var/www/html/util.php';

$checker = new ApiChecker();
$checker->demandPost();
$checker->demandArg("username");
$checker->demandArg("password");
$checker->check();

echo '{ "pass": true }';

?>
