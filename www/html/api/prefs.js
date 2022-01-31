// two javascript APIs call the same underlying PHP API

api.updatePrefs = (goalPri, stepPri, stepState, passFunc, errFunc = null) =>
{
   var msg = new ServerMessage("api/prefs.php",[passFunc,errFunc]);
   msg.run({GoalPri:goalPri,StepPri:stepPri,StepState:stepState});
}

api.getPrefs = (passFunc, errFunc = null) =>
{
   var msg = new ServerMessage("api/prefs.php",[passFunc,errFunc]);
   msg.run({GoalPri:".",StepPri:".",StepState:"."});
}
