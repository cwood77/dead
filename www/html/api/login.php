<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

$checker = new ApiChecker();
$username = $checker->demandArg("username");
$password = $checker->demandArg("password");
$checker->check();

$db = new Db();

$user = $db->findUser($username);
if ($user != null)
{
   try
   {
      if ($user->login($password))
      {
         echo '{ "pass": true }';
      }
      else
      {
         echo "bad password";
      }
   }
   catch(PDOException $x)
   {
      echo "DB error: " . $x->getMessage();
   }
}
else
{
   echo $username . " does not exist";
}

?>
