api.updateStep = (stepId, state, priority, title, passFunc, errFunc = null) =>
{
   var msg = new ServerMessage("api/updateStep.php",[passFunc,errFunc]);
   msg.run({StepID:stepId,S:state,P:priority,T:title});
}
