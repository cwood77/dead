<?php

require 'util.php';
require 'db.php';

leaveIfNoSession();

?>

<html>
<head>
<link rel="stylesheet" href="main.css"/>
<title>Dead</title>
<script>

function addRowHandlers() {
  var table = document.getElementById("tableId");
  var rows = table.getElementsByTagName("tr");
  for (i = 0; i < rows.length; i++) {
    var currentRow = table.rows[i];
    var createClickHandler = function(row) {
      return function() {
        document.location='edit.php?AsUser=<?php echo $_SESSION['username']; ?>&id=' + row.id;
      };
    };
    currentRow.onclick = createClickHandler(currentRow);
  }
}

</script>
</head>
<body onload="addRowHandlers()">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php echo "Welcome " . $_SESSION['username']; ?><br/>
<br/>

<button onclick="document.location='add.php?AsUser=<?php echo $_SESSION['username']; ?>'">Add</button><br/>
<br/>

<table id="tableId">
<tr><th class="icon1"></th><th>Pri</th><th>Goal</th></tr>
<?php

try
{
   $db = new Db();
   $user = $db->findUser($_SESSION['username']);
   $goals = $user->listGoals();
   foreach($goals as $goal)
   {
      echo "<tr id='" . $goal['id'] . "'>";
      switch($db->getGoalStepState($goal['id']))
      {
         case 'nosteps':
            echo "<td class='icon'><img src='nosteps.png'></td>";
            break;
         case 'inwork':
            echo "<td class='icon'><img src='inwork.png'></td>";
            break;
         case 'ready':
            echo "<td class='icon'><img src='blank.png'></td>";
            break;
         case 'blocked':
            echo "<td class='icon'><img src='blocked.png'></td>";
            break;
         case 'complete':
            echo "<td class='icon'><img src='done.png'></td>";
            break;
      }
      echo "<td>" . $goal['priority'] . "</td>";
      echo "<td>" . $goal['title'] . "</td></tr>";
   }
}
catch(PDOException $x)
{
   echo "DB error: " . $x->getMessage();
}

?>
</table>
<br/>
<button onclick="document.location='account.php'">Account</button>

</body>
</html>
