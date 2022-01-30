<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

leaveIfNoSession();
$checker = new ApiChecker();
$stepId = $checker->demandArg("StepID");
$checker->check();

try
{
   $db = new Db();
   $db->deleteStep($stepId);

   echo '{ "pass": true, "sid": "' . htmlspecialchars(SID). '" }';
}
catch(PDOException $x)
{
   echo "DB error: " . $x->getMessage();
}
?>
