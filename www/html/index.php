<?php

require 'util.php';

session_start();
if (!empty($_SESSION['username']))
{
   redirect('dashboard.php');
}

?>

<html>
<head>
<link rel="stylesheet" href="main.css"/>
<title>Dead</title>

<?php require 'api.php'; includeJsApis(array("addUser","login")); ?>
<script>

function onload()
{
   // autosubmit on enter
   document.addEventListener("keyup", function(event)
   {
      if (event.keyCode === 13)
      {
         submit();
      }
   });
}

function submit()
{
   // client-side validation
   var error = checkIfEmpty("username");
   error += checkIfEmpty("password");
   document.getElementById("message").innerHTML = error;
   if (error.length != 0)
   {
      return;
   }

   // latch vars
   var u = document.getElementById("username").value;
   var p = document.getElementById("password").value;
   var add = document.getElementById("newacct").checked;

   var showMessage = (m) =>
   {
      document.getElementById("message").innerHTML = m;
   }
   var good = (json) =>
   {
      window.location.href="dashboard.php";
   }

   // sign-up or log-in
   if (add)
   {
      api.addUser(u,p,good,showMessage);
   }
   else
   {
      api.login(u,p,good,showMessage);
   }
}

</script>

</head>

<body onload="onload()">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<center>

<img src="logo.png"><br/>
<p class="tagline">Do not go gentle into that good night</p>

<form>
Create a new account: <input type="checkbox" id="newacct"/><br/>
username: <input type="text" id="username", name="username"><br/>
password: <input type="password" id="password", name="password"><br/>
<p class="error" id="message"/>
</form>
<button onclick="submit()">Submit</button>

</center>
</body>
</html>
