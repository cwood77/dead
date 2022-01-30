api.deleteStep = (stepId, passFunc, errFunc = null) =>
{
   var msg = new ServerMessage("api/deleteStep.php",[passFunc,errFunc]);
   msg.run({StepID:stepId});
}
