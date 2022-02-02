api.saveMilestones = (events, passFunc, errFunc = null) =>
{
   var msg = new ServerMessage("api/timeline.php",[passFunc,errFunc]);
   msg.run({Op:"save",Events:events});
}

api.loadMilestones = (passFunc, errFunc = null) =>
{
   var msg = new ServerMessage("api/timeline.php",[passFunc,errFunc]);
   msg.run({Op:"load"});
}
