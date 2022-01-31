<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

leaveIfNoSession();
$checker = new ApiChecker();
$goalPri = $checker->demandArg("GoalPri");
$stepPri = $checker->demandArg("StepPri");
$stepState = $checker->demandArg("StepState");
$checker->check();

try
{
   $db = new Db();

   if ($goalPri != ".")
   {
      $db->setUserPrefs($_SESSION['username'],$goalPri,$stepPri,$stepState);
   }

   $row = $db->getUserPrefs($_SESSION['username']);

   echo '{ "pass": true, "sid": "' . htmlspecialchars(SID)
      . '", "goalPri": "' . $row['goalPriority']
      . '", "stepPri": "' . $row['stepPriority']
      . '", "stepState": "' . $row['stepState']
      . '" }';
}
catch(PDOException $x)
{
   echo "DB error: " . $x->getMessage();
}
?>
