api.toggleDashboard = (doToggle, passFunc, errFunc = null) =>
{
   var msg = new ServerMessage("api/toggleDashboard.php",[passFunc,errFunc]);
   msg.run({DoToggle:doToggle});
}
