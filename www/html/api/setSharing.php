<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

$checker = new ApiChecker();
$looker = $checker->demandArg("Looker");
$allowed = $checker->demandArgBool("Allowed");
$checker->check();
session_start();

$db = new Db();

$user = $db->findUser($_SESSION['username']);
if ($user != null)
{
   try
   {
      $user->setSharing($looker,$allowed);
      echo '{ "pass": true, "sid": "' . htmlspecialchars(SID). '" }';
   }
   catch(PDOException $x)
   {
      echo "DB error: " . $x->getMessage();
   }
}
else
{
   echo $_SESSION['username'] . " not found";
}

?>
