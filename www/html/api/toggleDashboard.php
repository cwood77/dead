<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';
require '/var/www/html/_timeline.php';

leaveIfNoSession();
$checker = new ApiChecker();
$doToggle = $checker->demandArgBool("DoToggle");
$checker->check();

try
{
   $html = "";
   $buttonLbl = "";

   $state = $_SESSION['dashboardState'];
   if($state == null)
   {
      $state = "goals";
   }

   if ($doToggle)
   {
      if ($state == "goals")
      {
         $state = "steps";
      }
      else
      {
         $state = "goals";
      }

      $_SESSION['dashboardState'] = $state;
   }

   // ------------------------------------------------------------------- GOAL MODE -----------------------------------------
   if ($state == "goals")
   {
      $buttonLbl = "Show steps";
      $html .= '<tr><th></th><th class=\"icon1\"></th>';
      if ($_SESSION['showall'])
      {
         $html .= "<th>User</th>";
      }
      $html .= '<th>Pri</th><th>Goal</th></tr>';
      $db = new Db();
      $user = $db->findUser($_SESSION['username']);
      $goals = $user->listGoals($_SESSION['showall']);
      $colorer = new EventColorer($user->timeline());
      foreach($goals as $goal)
      {
         $goalObj = new Goal($db, $goal['id'], $goal['title'], $goal['priority']);

         $html .= "<tr onclick='clickRow(" . $goal['id'] . ")'>";
         $html .= "<td style='background-color: #" . $colorer->getColorOf($goalObj->getMilestone()) . "'></td>";
         switch($db->getGoalStepState($goal['id']))
         {
            case 'nosteps':
               $html .= "<td class='icon'><img src='nosteps.png'></td>";
               break;
            case 'inwork':
               $html .= "<td class='icon'><img src='inwork.png'></td>";
               break;
            case 'ready':
               $html .= "<td class='icon'><img src='ready.png'></td>";
               break;
            case 'blocked':
               $html .= "<td class='icon'><img src='blocked.png'></td>";
               break;
            case 'complete':
               $html .= "<td class='icon'><img src='done.png'></td>";
               break;
         }
         if ($_SESSION['showall'])
         {
            $html .= "<td>" . $goal['userName'] . "</td>";
         }
         $html .= "<td>" . $goal['priority'] . "</td>";
         $html .= "<td>" . $goal['title'] . "</td></tr>";
      }
   }
   // ------------------------------------------------------------------- GOAL MODE -----------------------------------------
   else
   {
      $buttonLbl = "Show goals";
      $html .= '<tr><th class=\"other\" class=\"icon1\"></th>';
      if ($_SESSION['showall'])
      {
         $html .= '<th class=\"other\">User</th>';
      }
      $html .= '<th class=\"other\">Pri</th><th class=\"other\">Goal</th><th class=\"other\">Step</th></tr>';
      $db = new Db();
      $user = $db->findUser($_SESSION['username']);
      $steps = $user->listSteps($_SESSION['showall']);
      foreach($steps as $step)
      {
         $html .= "<tr onclick='clickRow(" . $step[4] . ")'>";
         switch($step[2])
         {
            case 'inwork':
               $html .= "<td class='icon'><img src='inwork.png'></td>";
               break;
            case 'ready':
               $html .= "<td class='icon'><img src='ready.png'></td>";
               break;
            case 'blocked':
               $html .= "<td class='icon'><img src='blocked.png'></td>";
               break;
            case 'complete':
               $html .= "<td class='icon'><img src='done.png'></td>";
               break;
         }
         if ($_SESSION['showall'])
         {
            $html .= "<td>" . $step['5'] . "</td>";
         }
         $html .= "<td>" . $step['1'] . "</td>";
         $html .= "<td>" . $step['3'] . "</td>";
         $html .= "<td>" . $step['0'] . "</td></tr>";
      }
   }

   echo '{ "pass": true, "sid": "' . htmlspecialchars(SID)
      . '", "html": "' . $html
      . '", "button": "' . $buttonLbl
      . '" }';
}
catch(PDOException $x)
{
   echo "DB error: " . $x->getMessage();
}
?>
