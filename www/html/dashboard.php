<?php

require 'util.php';
require 'db.php';

leaveIfNoSession();

?>

<html>
<head>
<link rel="stylesheet" href="main.css"/>
<title>Dead</title>
<?php require 'api.php'; includeJsApi("toggleDashboard"); ?>
<script>

function load()
{
   toggleView(false);
}

function toggleView(doToggle)
{
   var good = function(json)
   {
      document.getElementById("tableId").innerHTML = json['html'];
      document.getElementById("toggleBtn").innerHTML = json['button'];
   }
   api.toggleDashboard(doToggle,good);
}

function clickRow(goalId)
{
   document.location='edit.php?AsUser=<?php echo $_SESSION['username']; ?>&id=' + goalId;
}

</script>
</head>
<body onload="load()">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php echo "Welcome " . $_SESSION['username']; ?><br/>
<br/>

<button onclick="document.location='add.php?AsUser=<?php echo $_SESSION['username']; ?>'">Add</button><br/>
<br/>
<button id="toggleBtn" onclick="toggleView(true)">By Step</button>
<br/>
<table id="tableId"></table>
<br/>
<button onclick="document.location='account.php'">Account</button>

</body>
</html>
