<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

leaveIfNoSession();
$checker = new ApiChecker();
$op = $checker->demandArg("Op");
$events = null;
if ($op == "save")
{
   $events = json_decode($checker->demandArg("Events"));
}
$checker->check();

try
{
   $db = new Db();
   $user = $db->findUser($_SESSION['username']);
   $tl = $user->timeline();

   if ($op == "save")
   {
      $tl->setEvents($events);
      echo '{ "pass": true, "sid": "' . htmlspecialchars(SID). '"}';
   }
   else
   {
      // load case
      $events = $tl->listEvents();
      $json = array();
      foreach($events as $event)
      {
         array_push($json,array($event->deadline,$event->name));
      }

      echo '{ "pass": true, "sid": "' . htmlspecialchars(SID). '", "events": ' . json_encode($json) . ' }';
   }
}
catch(PDOException $x)
{
   echo "DB error: " . $x->getMessage();
}
?>
