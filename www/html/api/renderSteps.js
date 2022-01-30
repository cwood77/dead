api.renderSteps = (goalId, passFunc, errFunc = null) =>
{
   var msg = new ServerMessage("api/renderSteps.php",[passFunc,errFunc]);
   msg.run({GoalID:goalId});
}
