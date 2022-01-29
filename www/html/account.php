<?php

require 'util.php';
require 'db.php';

leaveIfNoSession();

?>

<html>
<head>
<link rel="stylesheet" href="main.css"/>
<title>Dead</title>
<?php require 'api.php'; includeJsApi("logout"); ?>
<script>

function logout()
{
   var good = () =>
   {
      window.location.href="index.php";
   }

   api.logout(good);
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
   $users = $db->listUsers();
   echo count($users) . " user(s)";
}
catch(PDOException $x)
{
   echo "DB error: " . $x->getMessage();
}
?><br/>

<table>
<tr><th>Username</th></tr>
<?php
foreach($users as $user)
{
   echo "<tr><td>" . $user['userName'] . "</td></tr>";
}

?>
</table>
<br/>
<button onclick="logout()">Logout</button>

</body>
</html>
