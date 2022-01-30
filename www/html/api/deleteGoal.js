api.deleteGoal = (goalId, owningUser, passFunc=null, errFunc = null) =>
{
   var msg = new ServerMessage("api/deleteGoal.php",[passFunc,errFunc]);
   msg.run({GoalID:goalId,As:owningUser});
}
