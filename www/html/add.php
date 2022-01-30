<?php

require 'util.php';

leaveIfNoSession();
$checker = new FrontEndChecker();
$owningUser = $checker->demandArg("AsUser");
$checker->check();

?>

<html>
<head>
<link rel="stylesheet" href="main.css"/>
<title>Dead</title>
<?php require 'api.php'; includeJsApi("addGoal"); ?>
<script>
<?php echo 'var _owningUser = "' . $owningUser . '";'; ?>

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

</script>
</head>
<body>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<form>
name: <input type="text" id="name" class="wide"><br/>
priority: <select id="priority">
   <option>1</option>
   <option>2</option>
   <option>3</option>
   <option selected>4</option>
</select><br/>
<p class="error" id="message"/>
</form>
<button onclick="submit()">Submit</button>

</body>
</html>
