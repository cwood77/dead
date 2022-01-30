<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

leaveIfNoSession();
$checker = new ApiChecker();
$goalId = $checker->demandArg("GoalID");
$owningUser = $checker->demandArg("As");
$checker->check();

$db = new Db();

$user = $db->findUser($owningUser);
if ($user != null)
{
   try
   {
      $goal = $user->queryGoal($goalId);
      $goal->delete();
      echo '{ "pass": true, "sid": "' . htmlspecialchars(SID). '" }';
   }
   catch(PDOException $x)
   {
      echo "DB error: " . $x->getMessage();
   }
}
else
{
   echo $owningUser . " doesn't exist?";
}

?>
