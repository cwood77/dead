<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

leaveIfNoSession();
$checker = new ApiChecker();
$goalId = $checker->demandArg("GoalID");
$checker->check();

try
{
   $db = new Db();
   $results = $db->querySteps($goalId);

   $html = "<tr><th>State</th><th>Pri</th><th>Step</th></tr>";
   foreach($results as $row)
   {
      $rowText
         = "<tr onclick='editStep(" . $row['id'] . ',\"' . $row['state'] . '\",\"' . $row['priority'] . '\",\"' . $row['title'] . '\"' . ")'>"

         . "<td>"
         . $row['state']
         . "</td>"

         . "<td>"
         . $row['priority']
         . "</td>"

         . "<td>"
         . $row['title']
         . "</td>"

         . "</tr>"
         ;
      $html .= $rowText;
   }

   echo '{ "pass": true, "sid": "' . htmlspecialchars(SID). '", "stepTableHtml": "' . $html . '" }';
}
catch(PDOException $x)
{
   echo "DB error: " . $x->getMessage();
}
?>
