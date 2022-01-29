<html>
<head>
<?php require 'api.php'; includeJsApi("addUser"); ?>
</head>
<body>
<button onclick="addUser('a','b',function(x) { alert('pass'); })">Test</button>
<button onclick="api.addUser('a','b',function(x) { alert('pass'); })">Test</button>
</body>
</html>
