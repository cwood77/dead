<?php

require 'util.php';
require 'db.php';

session_start();
if (empty($_SESSION['username']))
{
   redirect('index.php');
}

?>

<html>
<head>
<link rel="stylesheet" href="main.css"/>
<title>Dead</title>
</head>
<body>
<?php echo "Welcome " . $_SESSION['username']; ?><br/>
<br/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<button onclick="document.location='add.php?AsUser=<?php echo $_SESSION['username']; ?>'">Add</button><br/>
<br/>

<table>
<tr><th>Priority</th><th>Item</th></tr>
<?php

try
{
   $db = new Db();
   $user = $db->findUser($_SESSION['username']);
   $goals = $user->listGoals();
   foreach($goals as $goal)
   {
      echo "<tr><td>" . $goal['priority'] . "</td><td>" . $goal['title'] . "</td></tr>";
   }
}
catch(PDOException $x)
{
   echo "DB error: " . $x->getMessage();
}

?>
</table>

</body>
</html>
