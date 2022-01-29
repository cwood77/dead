api.logout = (passFunc, errFunc = null) =>
{
   var msg = new ServerMessage("api/logout.php",[passFunc,errFunc]);
   msg.run({});
}
