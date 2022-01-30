api.editGoal = (goalId, owningUser, name, priority, passFunc=null, errFunc = null) =>
{
   var msg = new ServerMessage("api/editGoal.php",[passFunc,errFunc]);
   msg.run({GoalID:goalId,As:owningUser,Name:name,Priority:priority});
}
