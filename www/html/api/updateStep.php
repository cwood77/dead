<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

leaveIfNoSession();
$checker = new ApiChecker();
$stepId = $checker->demandArg("StepID");
$state = $checker->demandArg("S");
$priority = $checker->demandArg("P");
$title = $checker->demandArg("T");
$checker->check();

try
{
   $db = new Db();
   $db->modifyStep($stepId, $state, $priority, $title);

   echo '{ "pass": true, "sid": "' . htmlspecialchars(SID). '" }';
}
catch(PDOException $x)
{
   echo "DB error: " . $x->getMessage();
}
?>
