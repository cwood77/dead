<?php

require '/var/www/html/util.php';
require '/var/www/html/db.php';

leaveIfNoSession();
$checker = new ApiChecker();
$userId = $checker->demandArg("UserID");
$op = $checker->demandArg("Op");
$checker->check();

try
{
   $db = new Db();

   if ($userId != ".")
   {
      $db->modUser($userId,$op);
   }

   $me = $db->findUser($_SESSION['username']);
   $sharing = $me->getSharingInfo();
   $users = $db->listUsers();

   $html = "<tr><th>Username</th><th>Superuser</th><th>Share my goals with</th>";
   if ($me->isSuper())
   {
      $html .= "<th></th>";
   }
   $html .= "</tr>";
   foreach($users as $user)
   {
      $superuser = "";
      if ($user['superuser'])
      {
         $superuser = "Y";
      }

      $html .= "<tr><td>" . $user['userName'] . "</td><td>" . $superuser . "</td>";

      if ($_SESSION['username'] == $user['userName'])
      {
         $html .= "<td>n/a</td>";
      }
      else
      {
         $html .= "<td><input type='checkbox'";
         if($sharing[$user['userName']] == true)
         {
            $html .= " checked";
         }
         $html .= " onclick='updateSharing(this.checked," . '\"' . $user['userName'] . '\"' . ")'></td>";
      }

      if ($me->isSuper())
      {
         $html .= "<td>"
            . '<button onclick=\"modUser(' . "'" . $user['id'] . "','s'" . ')\">Super</button>'
            . '<button onclick=\"modUser(' . "'" . $user['id'] . "','f'" . ')\">Forgot</button>'
            . '<button class=\"deleteBtn\" onclick=\"modUser(' . "'" . $user['id'] . "','d'" . ')\">Delete</button>'
            . "</td>";
      }

      $html .= "</tr>";
   }

   echo '{ "pass": true, "sid": "' . htmlspecialchars(SID). '", "userTableHtml": "' . $html . '", "count": ' . count($users) . ' }';
}
catch(PDOException $x)
{
   echo "DB error: " . $x->getMessage();
}
?>
