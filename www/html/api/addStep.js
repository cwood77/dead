api.addStep = (goalId, passFunc, errFunc = null) =>
{
   var msg = new ServerMessage("api/addStep.php",[passFunc,errFunc]);
   msg.run({GoalID:goalId});
}
