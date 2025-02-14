<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

leaveIfNoSession();
$checker = new ApiChecker();
$owningUser = $checker->demandArg("As");
$name = $checker->demandArg("Name");
$priority = $checker->demandArg("Priority");
$checker->check();

$db = new Db();

$user = $db->findUser($owningUser);
if ($user != null)
{
   try
   {
      $goal = $user->addGoal($name);
      $goal->setPriority($priority);
      $goal->addHistory($_SESSION['username'],"goal created");
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
