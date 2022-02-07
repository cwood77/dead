<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

leaveIfNoSession();
$checker = new ApiChecker();
$op = $checker->demandArg("Op");
$key = $checker->demandArg("Key");
$jsonStr = null;
if ($op == "set")
{
   $jsonStr = $checker->demandArg("JsonStr");
}
$checker->check();

try
{
   $db = new Db();

   if($op == "get")
   {
      // -------------------------------------------------- get

      echo '{ "pass": true, "sid": "' . htmlspecialchars(SID)
         . '", "jsonStr": "' . addslashes($_SESSION[$key])
         . '" }';
   }
   else
   {
      // -------------------------------------------------- set

      $_SESSION[$key] = $jsonStr;

      echo '{ "pass": true, "sid": "' . htmlspecialchars(SID)
         . '" }';
   }
}
catch(PDOException $x)
{
   echo "DB error: " . $x->getMessage();
}
?>
