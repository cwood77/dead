<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

leaveIfNoSession();
$checker = new ApiChecker();
$op = $checker->demandArg("Op");
$owningUser = $checker->demandArg("As");
$goalId = $checker->demandArg("GoalID");
$comment = null;
if ($op == "add")
{
   $comment = $checker->demandArg("Comment");
}
$checker->check();

$db = new Db();

$user = $db->findUser($owningUser);
if ($user != null)
{
   try
   {
      $goal = $user->queryGoal($goalId);
      if ($op == "add")
      {
         $goal->addHistory($_SESSION['username'],$comment);
      }

      $history = $goal->listHistory();
      $html = "<tr><th>Date</th><th>User</th><th>Event</th></tr>";
      foreach($history as $histItem)
      {
         $html .= "<tr><td>" . $histItem[0] . "</td><td>" . $histItem[1] . "</td><td>" . $histItem[2] . "</td></tr>";
      }

      echo '{ "pass": true, "sid": "' . htmlspecialchars(SID). '", "html": "' . $html . '" }';
   }
   catch(PDOException $x)
   {
      echo "DB error: " . $x->getMessage();
   }
}
else
{
   echo $owningUser . " doesn't exist?";
}

?>
