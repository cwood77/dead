<?php

require 'util.php';
require 'db.php';

leaveIfNoSession();

?>

<html>
<head>
<link rel="stylesheet" href="main.css"/>
<title>Dead</title>
<?php require 'api.php'; includeJsApis(array("logout","setSharing")); ?>
<script>

function logout()
{
   var good = () =>
   {
      window.location.href="index.php";
   }

   api.logout(good);
}

function updateSharing(checked, looker)
{
   api.setSharing(looker, checked, function(){});
}

</script>
</head>
<body>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<br/>

<?php
try
{
   $db = new Db();
   $user = $db->findUser($_SESSION['username']);
   $sharing = $user->getSharingInfo();
   $users = $db->listUsers();
   echo count($users) . " user(s)";
}
catch(PDOException $x)
{
   echo "DB error: " . $x->getMessage();
}
?><br/>

<table>
<tr><th>Username</th><th>Superuser</th><th>Share my goals with</th></tr>
<?php
foreach($users as $user)
{
   $superuser = "";
   if ($user['superuser'])
   {
      $superuser = "Y";
   }

   echo "<tr><td>" . $user['userName'] . "</td><td>" . $superuser . "</td>";

   if ($_SESSION['username'] == $user['userName'])
   {
      echo "<td></td></tr>";
   }
   else
   {
      echo "<td><input type='checkbox'";
      if($sharing[$user['userName']] == true)
      {
         echo " checked";
      }
      echo " onclick='updateSharing(this.checked," . '"' . $user['userName'] . '"' . ")'></td></tr>";
   }
}

?>
</table>
<br/>
<button onclick="logout()">Logout</button>

</body>
</html>
