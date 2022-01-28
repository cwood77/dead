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
      $db->addUser($username,$password);
      echo "login successfull";
   }
   catch(PDOException $x)
   {
      return $x->getMessage();
   }
}
else
{
   echo $username . " already exists";
}

?>
