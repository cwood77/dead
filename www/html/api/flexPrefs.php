<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

leaveIfNoSession();
$checker = new ApiChecker();
$op = $checker->demandArg("Op");
$json = json_decode($checker->demandArg("JsonStr"));
$checker->check();

try
{
   $db = new Db();

   if($op == "get")
   {
      // -------------------------------------------------- get

      $members = get_object_vars($json);
      foreach($members as $name => $typeString)
      {
         $json->{ $name } = $db->getUserPref($name, $_SESSION['username']);
      }

      echo '{ "pass": true, "sid": "' . htmlspecialchars(SID)
         . '", "jsonStr": "' . addslashes(json_encode($json))
         . '" }';
   }
   else
   {
      // -------------------------------------------------- set

      $members = get_object_vars($json);
      foreach($members as $name => $typeString)
      {
         $value = $json->{ $name };
         $db->setUserPref($_SESSION['username'], $name, $value);
      }

      echo '{ "pass": true, "sid": "' . htmlspecialchars(SID)
         . '" }';
   }
}
catch(PDOException $x)
{
   echo "DB error: " . $x->getMessage();
}
?>
