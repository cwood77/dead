<?php

require 'util.php';
require 'db.php';

demandHttpPost();
$username = nonEmptyArg("username");
$password = nonEmptyArg("password");

$db = new Db();

$user = $db->findUser($username);
if ($user != null)
{
   try
   {
      if ($user->login($password))
      {
         echo "login successfull";
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
