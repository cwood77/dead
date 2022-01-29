<?php

require 'util.php';
require 'db.php';

demandHttpPost();
$username = nonEmptyArg("username");
$password = nonEmptyArg("password");

$db = new Db();

$user = $db->findUser($username);
if ($user == null)
{
   try
   {
      $db->addUser($username,password_hash($password, PASSWORD_DEFAULT));
      echo "login successfull";
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
