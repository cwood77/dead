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
      // attempt to sign up
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
         if (this.readyState == 4 /*&& this.status == 200*/) {
            document.getElementById("message").innerHTML = this.responseText;
            if (this.responseText == "login successfull")
            {
               window.location.href="dashboard.php";
            }
         }
      };
      xmlhttp.open(
         "POST",
         "_signup.php?"
            + "username=" + document.getElementById("username").value
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

<h1>Create an Account</h1>
<form>
username: <input type="text", id="username", name="username"><br/>
password: <input type="password", id="password", name="password"><br/>
<p class="error" id="message"/>
</form>
<button onclick="submit()"/>Submit</button>

</center>
</body>
</html>
