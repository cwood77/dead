<?php

require 'util.php';
require 'db.php';
require '_timeline.php';

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
<?php require 'api.php'; includeJsApis(array("editGoal","deleteGoal","renderSteps","addStep","deleteStep","updateStep","comments")); ?>
<script src="toggle.js"></script>
<script>
<?php echo 'var _owningUser = "' . $owningUser . '";'; ?>
<?php echo 'var _goalId = "' . $goalId . '";'; ?>
var _stepId = 0;

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

   populateMilestonesAndWarn();

   refreshStepTable();

   var updateComments = function(json)
   {
      document.getElementById("commentTable").innerHTML = json['html'];
   }
   api.getComments(_owningUser,_goalId,updateComments);
}

function populateMilestonesAndWarn()
{
   var ans = "<?php echo $goal->getMilestone(); ?>";

   var milestones = <?php echo generateTimelineJsEvents(); ?>;
   var optionHtml = "";
   var found = false;
   for(var i=0;i<milestones.length;i++)
   {
      var selected = "";
      if(milestones[i] == ans)
      {
         selected = " selected";
         found = true;
      }
      optionHtml += "<option" + selected + ">" + milestones[i] + "</option>";
   }
   if(!found)
   {
      if(ans == "(none)")
      {
      }
      else if(ans == "")
      {
      }
      else
      {
         document.getElementById("milestone-link-warning").innerHTML = "milestone '" + ans + "' dosen't exist";
      }
      optionHtml += "<option selected>(none)</option>";
   }
   else
   {
      optionHtml += "<option>(none)</option>";
   }

   document.getElementById("milestone").innerHTML = optionHtml;
}

function onMilestoneChanged()
{
   document.getElementById("milestone-link-warning").innerHTML = "";
}

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
   var milestone = document.getElementById("milestone").value;
   var desc = document.getElementById("desc").value;

   var good = (json) =>
   {
      window.location.href="dashboard.php";
   }
   api.editGoal(_goalId,_owningUser,name,priority,desc,milestone,good);
}

function deleteGoal()
{
   var good = (json) =>
   {
      window.location.href="dashboard.php";
   }
   api.deleteGoal(_goalId,_owningUser,good);
}

function refreshStepTable()
{
   var good = function(json)
   {
      document.getElementById("stepTable").innerHTML = json['stepTableHtml'];
   }
   api.renderSteps(_goalId,good);
}

function addDummyStep()
{
   api.addStep(_goalId,function(){ refreshStepTable(); });
}

function deleteStep(stepId)
{
   api.deleteStep(stepId,function(){ refreshStepTable(); });
   toggle(addingStep);
}

function maybeSelectText(control)
{
   if (control.value == "--New Step--")
   {
      control.setSelectionRange(0, control.value.length);
   }
}

function editStep(stepId, state, priority, title)
{
   _stepId = stepId;
   document.getElementById("editStepState").getElementsByClassName(state)[0].selected = 'selected';
   document.getElementById("editStepPriority").getElementsByTagName("option")[priority-1].selected = 'selected';
   document.getElementById("editStepTitle").value = title;
   toggle(addingStep,true);
}

function updateStep()
{
   var state = document.getElementById("editStepState").value;
   var priority = document.getElementById("editStepPriority").value;
   var title = document.getElementById("editStepTitle").value;
   api.updateStep(_stepId,state,priority,title,function(){ refreshStepTable(); });
   toggle(addingStep);
}

function addComment()
{
   var control = document.getElementById("newCommentText");

   var updateComments = function(json)
   {
      document.getElementById("commentTable").innerHTML = json['html'];
      control.value = "";
      toggle(addingComment);
   }
   api.addComments(_owningUser,_goalId,control.value,updateComments);
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
milestone: <select id="milestone" onchange="onMilestoneChanged()">
</select><p class="error" id="milestone-link-warning"></p>
Description:</br>
<textarea id="desc" rows="7" cols="100%"><?php echo $goal->getDesc(); ?></textarea>
<p class="error" id="message"/>
</form>
<button onclick="updateGoal()">Save</button>

<!-- steps -->
<hr>
<button onclick="addDummyStep()">Add Step</button><br/>
<!-- add panel -->
<div name="addingStep">
<br/>
   State: <select id="editStepState">
      <option class="ready">ready</option>
      <option class="inwork">inwork</option>
      <option class="blocked">blocked</option>
      <option class="complete">complete</option>
      <option class="cancelled">cancelled</option>
   </select>
   Priority: <select id="editStepPriority">
      <option>1</option>
      <option>2</option>
      <option>3</option>
      <option>4</option>
   </select>
   Step:<input type="text" class="wide" id="editStepTitle" onclick="maybeSelectText(this)"><br/>
<br/>
<button onclick="updateStep()">Update</button>      <button onclick="toggle(addingStep)">Cancel</button>    <button onclick="deleteStep(_stepId)" class="deleteBtn">Delete Step</button><br/></div>
<!-- display -->
<br/>
<table id="stepTable">
</table>
<br/>

<!-- comments -->
<hr>
<button name="addingCommentStart" onclick="toggle(addingComment)">Add Comment</button><br/>
<!-- add panel -->
<div name="addingComment">
<br/>
Comments:<input type="text" class="wide" id="newCommentText"><br/>
<br/>
<button onclick="addComment()">Add</button>    <button onclick="toggle(addingComment)">Cancel</button><br/></div>
<!-- display -->
<br/>
<table id="commentTable">
</table>
<br/>

<!-- global -->
<hr>
<button onclick="deleteGoal()" class="deleteBtn">Delete Goal</button>

</body>
</html>
