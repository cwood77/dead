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
<?php require 'api.php'; includeJsApis(array("editGoal","deleteGoal","renderSteps","addStep","deleteStep","updateStep")); ?>
<script src="toggle.js"></script>
<script>
<?php echo 'var _owningUser = "' . $owningUser . '";'; ?>
<?php echo 'var _goalId = "' . $goalId . '";'; ?>
var _stepId = 0;

function updateGoal()
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
   api.editGoal(_goalId,_owningUser,name,priority,good);
}

function deleteGoal()
{
   var good = (json) =>
   {
      window.location.href="dashboard.php";
   }
   api.deleteGoal(_goalId,_owningUser,good);
}

let addingStep = {
   v: false,
   label: "addingStep"
};

let addingComment = {
   v: false,
   label: "addingComment"
};

function refreshStepTable()
{
   var good = function(json)
   {
      document.getElementById("stepTable").innerHTML = json['stepTableHtml'];
   }
   api.renderSteps(_goalId,good);
}

function onload()
{
   // init the panel
   toggle(addingStep,false);
   toggle(addingComment,false);

   // update the select control (no way to do this in HTML)
   document.getElementById("priority").getElementsByTagName("option")[<?php echo $goal->getPriority(); ?>-1].selected = 'selected';

   refreshStepTable();
}

function addComment()
{
   var control = document.getElementById("newCommentText");

   alert("unimpl'd");

   control.value = "";
   toggle(addingComment);
}

function addDummyStep()
{
   api.addStep(_goalId,function(){ refreshStepTable(); });
}

function deleteStep(stepId)
{
   toggle(addingStep,false);
   api.deleteStep(stepId,function(){ refreshStepTable(); });
}

function editStep(stepId, state, priority, title)
{
   _stepId = stepId;
   document.getElementById("editStepState").getElementsByClassName(state)[0].selected = 'selected';
   document.getElementById("editStepPriority").getElementsByTagName("option")[priority-1].selected = 'selected';
   document.getElementById("editStepTitle").value = title;
   toggle(addingStep);
}

function updateStep()
{
   var state = document.getElementById("editStepState").value;
   var priority = document.getElementById("editStepPriority").value;
   var title = document.getElementById("editStepTitle").value;
   api.updateStep(_stepId,state,priority,title,function(){ refreshStepTable(); });
   toggle(addingStep);
}

</script>
</head>
<body onload="onload()">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- basics -->
<form>
name: <input type="text" id="name" class="wide" value="<?php echo $goal->getTitle(); ?>"><br/>
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
<button onclick="addDummyStep()">Add Step</button><br/>
<!-- add panel -->
   <!--<button name="addingStepStart" onclick="toggle(addingStep)">Add Step</button><br/>-->
<div name="addingStep">
   State: <select id="editStepState">
      <option class="blocked">blocked</option>
      <option class="ready">ready</option>
      <option class="inwork">inwork</option>
      <option class="complete">complete</option>
   </select>
   Priority: <select id="editStepPriority">
      <option>1</option>
      <option>2</option>
      <option>3</option>
      <option>4</option>
   </select>
   Step:<input type="text" class="wide" id="editStepTitle"><br/>
<button onclick="updateStep()">Update</button><button onclick="toggle(addingStep)">Cancel</button><br/></div>
<!-- display -->
<br/>
<table id="stepTable">
</table>
<br/>

<!-- comments -->
<hr>
<!-- add panel -->
<button name="addingCommentStart" onclick="toggle(addingComment)">Add Comment</button><br/>
<div name="addingComment">Comments:<input type="text" class="wide" id="newCommentText"><br/>
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
<button onclick="updateGoal()">Update</button>
<button onclick="deleteGoal()">Delete</button>

</body>
</html>
