<?php

require 'util.php';
//require '../secrets.php';

$username = "";
$error = "";

//$conn = new PDO("mysql:host=localhost;dbname=Dead", DBUSER, DBPASSWD);

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
   if (empty($_POST["username"]))
   {
      $error .= '<p class="error">username cannot be blank</p>';
   }
   else
   {
      $username = $_POST["username"];
   }
   if (empty($_POST["password"]))
   {
      $error .= '<p class="error">password cannot be blank</p>';
   }

   if (empty($error))
   {
      redirect("dashboard.php");
   }
}

?>
<html>
<head>
<link rel="stylesheet" href="index.css"/>
<title>Dead</title>
</head>

<body>
<center>

<img src="logo.png"><br/>
<p class="tagline">Do not go gentle into that good night</p>

<h1>Create an Account</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
username: <input type="text", name="username", value="<?php echo $username; ?>"><br/>
password: <input type="password", name="password"><br/>
<br/><input type="submit">
<?php echo $error; ?>
</form>

</center>
</body>
</html>
