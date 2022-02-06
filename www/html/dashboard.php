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
<?php require 'api.php'; includeJsApis(array("toggleDashboard","showAll","stash")); ?>
<script src="toggle.js"></script>
<script>

let filtering = {
   v: false,
   label: "filterToggle"
};

function load()
{
   toggle(filtering,false);
   loadFilter();
   toggleView(false);
}

function setShowAll(checkbox)
{
   var good = function()
   {
      toggleView(false);
   }
   api.showAll(checkbox.checked,good);
}

function toggleView(doToggle)
{
   var good = function(json)
   {
      document.getElementById("tableId").innerHTML = json['html'];
      document.getElementById("toggleBtn").innerHTML = json['button'];

      var allowFiltering = (json['button'] == "Show steps") // only support filters for goals for now
      toggle(filtering,false);
      document.getElementById("sortMode").style.display = allowFiltering ? "inline" : "none";
      document.getElementById("startFilteringBtn").style.display = allowFiltering ? "inline" : "none";
   }
   api.toggleDashboard(doToggle,good);
}

function loadFilter()
{
   var nothing = function() { };

   var good = function(json)
   {
      document.getElementById("fil-comp").checked = json['hideCompleted'];
      document.getElementById("fil-blc").checked = json['hideBlocked'];
      document.getElementById("fil-ida").checked = json['hideIdeas'];
      document.getElementById("fil-mile").checked = json['hideLaterMilestones'];

      var good2 = function(json2)
      {
         var searchVal = json2['sortMode'].replaceAll('&#32;', ' ');
         document.getElementById("sortMode").value = searchVal;
      }
      api.getStash('sort-stash',good2,nothing);
   }
   api.getStash('filter-stash',good,nothing);
}

function applySorting()
{
   api.setStash('sort-stash',{ 'sortMode': document.getElementById("sortMode").value });

   toggleView(false);
}

function applyFilter()
{
   toggle(filtering);

   var json = {
      'hideCompleted': false,
      'hideBlocked': false,
      'hideIdeas': false,
      'hideLaterMilestones': false
   };
   json['hideCompleted'] = document.getElementById("fil-comp").checked;
   json['hideBlocked'] = document.getElementById("fil-blc").checked;
   json['hideIdeas'] = document.getElementById("fil-ida").checked;
   json['hideLaterMilestones'] = document.getElementById("fil-mile").checked;
   api.setStash('filter-stash',json);

   toggleView(false);
}

function clickRow(goalId)
{
   document.location='edit.php?AsUser=<?php echo $_SESSION['username']; ?>&id=' + goalId;
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

<button id="toggleBtn" onclick="toggleView(true)"></button>
<select id="sortMode" onchange="applySorting()">
   <option selected>Sort by state</option>
   <option>Sort by priority</option>
</select>
<button id="startFilteringBtn" name="filterToggleStart" onclick="toggle(filtering)">Not filtering</button><br/>
<!-- filtering subpanel -->
<div name="filterToggle">
<br/>
<input id="fil-comp" type="checkbox">Hide completed<br/>
<br/>
<input id="fil-blc" type="checkbox">Hide blocked<br/>
<br/>
<input id="fil-ida" type="checkbox">Hide ideas<br/>
<br/>
<input id="fil-mile" type="checkbox">Hide later milestones<br/>
<br/>
<button onclick="applyFilter()">Apply</button>
</div>
<table id="tableId"></table>

<br/>
<button onclick="document.location='account.php'">Account</button>

</body>
</html>
