api.addGoal = (owningUser, name, priority, passFunc=null, errFunc = null) =>
{
   var msg = new ServerMessage("api/addGoal.php",[passFunc,errFunc]);
   msg.run({As:owningUser,Name:name,Priority:priority});
}
