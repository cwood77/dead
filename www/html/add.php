<?php

require 'util.php';

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
<?php require 'api.php'; includeJsApi("addGoal"); ?>
<script>

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
   api.addGoal(name,priority,good);
}

</script>
</head>
<body>

<form>
name: <input type="text" id="name"><br/>
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
