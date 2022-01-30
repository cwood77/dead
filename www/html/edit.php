<?php

require 'util.php';
require 'db.php';

leaveIfNoSession();
$checker = new FrontEndChecker();
$owningUser = $checker->demandArg("AsUser");
$goalId = $checker->demandArg("id");
$checker->check();

$db = new Db();
$user = $db->findUser($owningUser);
$goal = $user->queryGoal($goalId);

?>

<html>
<head>
<link rel="stylesheet" href="main.css"/>
<title>Dead</title>
<?php require 'api.php'; includeJsApi("addGoal"); ?>
<script src="toggle.js"></script>
<script>
<?php echo 'var _owningUser = "' . $owningUser . '";'; ?>
<?php echo 'var _goalId = "' . $goalId . '";'; ?>

function submit()
{
   // client-side validation
   var error = checkIfEmpty("name");
   document.getElementById("message").innerHTML = error;
   if (error.length != 0)
   {
      return;
   }

   // latch vars
   var name = document.getElementById("name").value;
   var priority = document.getElementById("priority").value;

   var good = (json) =>
   {
      window.location.href="dashboard.php";
   }
   api.addGoal(_owningUser,name,priority,good);
}

let addingStep = {
   v: false,
   label: "addingStep"
};

let addingComment = {
   v: false,
   label: "addingComment"
};

function onload()
{
   // init the panel
   toggle(addingStep,false);
   toggle(addingComment,false);

   // update the select control (no way to do this in HTML)
   document.getElementById("priority").getElementsByTagName("option")[<?php echo $goal->getPriority(); ?>-1].selected = 'selected';
}

function addComment()
{
   var control = document.getElementById("newCommentText");

   alert("unimpl'd");

   control.value = "";
   toggle(addingComment);
}

</script>
</head>
<body onload="onload()">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- basics -->
<form>
name: <input type="text" id="name" value="<?php echo $goal->getTitle(); ?>"><br/>
priority: <select id="priority">
   <option>1</option>
   <option>2</option>
   <option>3</option>
   <option>4</option>
</select><br/>
<p class="error" id="message"/>
</form>

<!-- steps -->
<hr>
<!-- add panel -->
<button name="addingStepStart" onclick="toggle(addingStep)">Add Step</button><br/>
<div name="addingStep">Priority: <input type="text">
Text:<input type="text"><br/>
<button>Add</button><button onclick="toggle(addingStep)">Cancel</button><br/></div>
<!-- display -->
<br/>
<table id="tableId">
<tr><th>State</th><th>Priority</th><th>Step</th></tr>
</table>
<br/>

<!-- comments -->
<hr>
<!-- add panel -->
<button name="addingCommentStart" onclick="toggle(addingComment)">Add Comment</button><br/>
<div name="addingComment">Comments:<input type="text" id="newCommentText"><br/>
<button onclick="addComment()">Add</button><button onclick="toggle(addingComment)">Cancel</button><br/></div>
<!-- display -->
<br/>
<table id="tableId">
<tr><th>Date</th><th>User</th><th>Event</th></tr>
<?php
$history = $goal->listHistory();
foreach($history as $histItem)
{
   echo "<tr><td>" . $histItem[0] . "</td><td>" . $histItem[1] . "</td><td>" . $histItem[2] . "</td></tr>";
}
?>
</table>
<br/>

<!-- global -->
<hr>
<button onclick="submit()">Submit</button>

</body>
</html>
