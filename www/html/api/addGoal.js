api.addGoal = (name, priority, passFunc=null, errFunc = null) =>
{
   var msg = new ServerMessage("api/addGoal.php",[passFunc,errFunc]);
   msg.run({Name:name,Priority:priority});
}
