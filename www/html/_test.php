<html>
<head>
<?php require 'api.php'; includeJsApis(array("addUser","login","flexPrefs")); ?>
<script>

function flexPrefs()
{
   json = {
      'goalPriority': 0,
      'stepState': 0
   };
   api.getFlexPrefs(json, function(json) { rest(parseInt(json['goalPriority'])); });
}

function rest(newVal)
{
   api.setFlexPrefs({ 'goalPriority': newVal+1 });
}

</script>
</head>
<body>
<button onclick="api.addUser('a','b',function(x) { alert('pass'); })">AddUser</button><br/>
<button onclick="api.login('a','b',function(x) { alert('pass'); })">Login</button><br/>
<button onclick="flexPrefs()">Test FlexPrefs</button>
</body>
</html>
