<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

session_start();
if (empty($_SESSION['username']))
{
   redirect('index.php');
}

$checker = new ApiChecker();
$name = $checker->demandArg("Name");
$priority = $checker->demandArg("Priority");
$checker->check();

$db = new Db();

$user = $db->findUser($_SESSION['username']);
if ($user != null)
{
   try
   {
      $goal = $user->addGoal($name);
      $goal->setPriority($priority);
      echo '{ "pass": true, "sid": "' . htmlspecialchars(SID). '" }';
   }
   catch(PDOException $x)
   {
      echo "DB error: " . $x->getMessage();
   }
}
else
{
   echo $_SESSION['username'] . " doesn't exist?";
}

?>
