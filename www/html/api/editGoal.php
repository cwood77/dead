<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

leaveIfNoSession();
$checker = new ApiChecker();
$goalId = $checker->demandArg("GoalID");
$owningUser = $checker->demandArg("As");
$name = $checker->demandArg("Name");
$priority = $checker->demandArg("Priority");
$desc = $checker->demandArg("Desc");
$mile = $checker->demandArg("Mile");
$checker->check();

$db = new Db();

$user = $db->findUser($owningUser);
if ($user != null)
{
   try
   {
      $goal = $user->queryGoal($goalId);
      $goal->setTitle($name);
      $goal->setPriority($priority);
      $goal->setDesc($desc);
      $goal->setMilestone($mile);
      $goal->addHistory($_SESSION['username'],"goal updated");
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

