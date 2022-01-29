<html>
<head>
<?php require 'api.php'; includeJsApis(array("addUser","login")); ?>
</head>
<body>
<button onclick="api.addUser('a','b',function(x) { alert('pass'); })">AddUser</button><br/>
<button onclick="api.login('a','b',function(x) { alert('pass'); })">Login</button><br/>
</body>
</html>
