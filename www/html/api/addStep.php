<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

leaveIfNoSession();
$checker = new ApiChecker();
$goalId = $checker->demandArg("GoalID");
$checker->check();

try
{
   $db = new Db();
   $db->addStep($_SESSION['username'],$goalId);

   echo '{ "pass": true, "sid": "' . htmlspecialchars(SID). '" }';
}
catch(PDOException $x)
{
   echo "DB error: " . $x->getMessage();
}
?>
