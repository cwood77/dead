<html>
<head>
<?php require 'api.php'; includeJsApis(array("addUser","login","flexPrefs","stash")); ?>
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

function stash()
{
   var next = function(json)
   {
      alert("got " + JSON.stringify(json));
      api.setStash("testing",{ 'something': true });
   }
   api.getStash("testing",next);
}

</script>
</head>
<body>
<button onclick="api.addUser('a','b',function(x) { alert('pass'); })">AddUser</button><br/>
<button onclick="api.login('a','b',function(x) { alert('pass'); })">Login</button><br/>
<button onclick="flexPrefs()">Test FlexPrefs</button><br/>
<button onclick="stash()">Test Stash</button><br/>
</body>
</html>
