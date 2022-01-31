<?php

require 'util.php';
require 'db.php';

leaveIfNoSession();

?>

<html>
<head>
<link rel="stylesheet" href="main.css"/>
<title>Dead</title>
<?php require 'api.php'; includeJsApis(array("logout","setSharing","modUser","prefs")); ?>
<script src="toggle.js"></script>
<script>

function onload()
{
   modUser(".",".");
   loadPrefs();
}

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

function modUser(userId, op)
{
   var good = function(json)
   {
      document.getElementById("summary").innerHTML = "site has " + json['count'] + " registered user(s)";
      document.getElementById("userTable").innerHTML = json['userTableHtml'];
   }
   api.modUser(userId,op,good);
}

let prefMod = {
   v: false,
   label: "prefMod"
};

function loadPrefs()
{
   toggle(prefMod,false);

   var good = function(json)
   {
      document.getElementById("defGoalPri").getElementsByTagName("option")[json['goalPri']-1].selected = 'selected';
      document.getElementById("defStepPri").value = json['stepPri'];
      document.getElementById("defStepState").value = json['stepState'];
   }
   api.getPrefs(good);
}

function savePrefs()
{
   toggle(prefMod,false);

   var good = function(json)
   {
      document.getElementById("defGoalPri").getElementsByTagName("option")[json['goalPri']-1].selected = 'selected';
      document.getElementById("defStepPri").value = json['stepPri'];
      document.getElementById("defStepState").value = json['stepState'];
   }
   api.updatePrefs(
      document.getElementById("defGoalPri").value,
      document.getElementById("defStepPri").value,
      document.getElementById("defStepState").value,
      good);
}

</script>
</head>
<body onload="onload()">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<p id="summary"></p>
<table id="userTable">
<tr><th>Username</th><th>Superuser</th><th>Share my goals with</th><th></th></tr>
</table>
<br/>
Preferences
   <button name="prefModStart" onclick="toggle(prefMod)">Modify</button>
   <button name="prefMod" onclick="savePrefs()" >Save</button>
   <button name="prefMod" onclick="loadPrefs()">Cancel</button> </br>
Default goal priority <select name="prefModDuring" id="defGoalPri">
   <option>1</option>
   <option>2</option>
   <option>3</option>
   <option>4</option>
</select> </br>
Default step priority <select name="prefModDuring" id="defStepPri">
   <option>1</option>
   <option>2</option>
   <option>3</option>
   <option>4</option>
</select> </br>
Default step state: <select name="prefModDuring" id="defStepState">
   <option class="blocked">blocked</option>
   <option class="ready">ready</option>
   <option class="inwork">inwork</option>
   <option class="complete">complete</option>
</select><br/>
<br/>
<button onclick="logout()">Logout</button>

</body>
</html>
