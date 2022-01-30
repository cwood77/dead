api.setSharing = (looker, allowed, passFunc, errFunc = null) =>
{
   var msg = new ServerMessage("api/setSharing.php",[passFunc,errFunc]);
   msg.run({Looker:looker,Allowed:allowed});
}
