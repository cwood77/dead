<?php

require 'util.php';
require 'db.php';

leaveIfNoSession();
$db = new Db();
$user = $db->findUser($_SESSION['username']);

?>

<html>
<head>
<link rel="stylesheet" href="main.css"/>
<title>Dead</title>
<?php require 'api.php'; includeJsApis(array("toggleDashboard","showAll")); ?>
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

function setShowAll(checkbox)
{
   var good = function()
   {
      toggleView(false);
   }
   api.showAll(checkbox.checked,good);
}

</script>
</head>
<body onload="load()">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php require '_timeline.php'; echo renderTimeline(); ?>
<?php echo "Welcome <b>" . $_SESSION['username']; echo ($user->isSuper() ? " [SUPERUSER]" : ""); ?></b> --- Show all users<?php echo ($user->isSuper()?"":" shared with me"); ?>: <input id="showAllCheckbox" type="checkbox" onclick="setShowAll(this)" <?php echo ($_SESSION['showall']) ? "checked" : ""; ?> ><br/>
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
