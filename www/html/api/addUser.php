<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

$checker = new ApiChecker();
$username = $checker->demandArg("username");
$password = $checker->demandArg("password");
$checker->check();

$db = new Db();

$user = $db->findUser($username);
if ($user == null)
{
   try
   {
      $db->addUser($username,password_hash($password, PASSWORD_DEFAULT));
      session_start();
      $_SESSION['username'] = $username;
      echo '{ "pass": true, "sid": "' . htmlspecialchars(SID). '" }';
   }
   catch(PDOException $x)
   {
      echo "DB error: " . $x->getMessage();
   }
}
else
{
   echo $username . " already exists";
}

?>
