<html>
<head>
<link rel="stylesheet" href="index.css"/>
<title>Dead</title>

<script>

function checkIfEmpty(name)
{
   var e = document.getElementById(name);
   if (e.value.length == 0)
   {
      return name + " cannot be empty<br/>";
   }
   return "";
}

function submit()
{
   var error = checkIfEmpty("username");
   error += checkIfEmpty("password");
   document.getElementById("message").innerHTML = error;

   if (error.length == 0)
   {
      // phone home
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
            document.getElementById("message").innerHTML = this.responseText;
            if (this.responseText == "login successfull")
            {
               window.location.href="dashboard.php";
            }
         }
      };

      var baseUrl = "_login.php";
      if (document.getElementById("newacct").checked)
      {
         baseUrl = "_signup.php";
      }

      xmlhttp.open(
         "POST",
         baseUrl
            + "?username=" + document.getElementById("username").value
            + "&password=" + document.getElementById("password").value,
         true);
      xmlhttp.send();
   }
}

</script>

</head>

<body>
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
