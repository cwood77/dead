<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

leaveIfNoSession();
$checker = new ApiChecker();
$all = $checker->demandArgBool("All");
$checker->check();

try
{
   $_SESSION['showall'] = $all;
   echo '{ "pass": true, "sid": "' . htmlspecialchars(SID). '"}';
}
catch(PDOException $x)
{
   echo "DB error: " . $x->getMessage();
}
?>
