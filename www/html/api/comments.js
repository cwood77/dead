api.addComments = (owningUser, goalId, newComment, passFunc=null, errFunc = null) =>
{
   var msg = new ServerMessage("api/comments.php",[passFunc,errFunc]);
   msg.run({Op:"add",As:owningUser,GoalID:goalId,Comment:newComment});
}

api.getComments = (owningUser, goalId, passFunc=null, errFunc = null) =>
{
   var msg = new ServerMessage("api/comments.php",[passFunc,errFunc]);
   msg.run({Op:"list",As:owningUser,GoalID:goalId});
}
