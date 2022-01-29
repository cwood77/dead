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
</head>
<body>
<?php echo "Welcome " . $_SESSION['username']; ?><br/>
<br/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<table>
<tr><th>Priority</th><th>Item</th></tr>
<tr><td>Priority</td><td>Item</td></tr>
<tr><td>Priority</td><td>Item</td></tr>
<tr><td>Priority</td><td>Item</td></tr>
<tr><td>Priority</td><td>Item</td></tr>
<tr><td>Priority</td><td>Item</td></tr>
<tr><td>Priority</td><td>Item</td></tr>
<tr><td>Priority</td><td>Item</td></tr>
<tr><td>Priority</td><td>Item</td></tr>
<tr><td>Priority</td><td>Item</td></tr>
</table>

</body>
</html>
