api.showAll = (all, passFunc, errFunc = null) =>
{
   var msg = new ServerMessage("api/showAll.php",[passFunc,errFunc]);
   msg.run({All:all});
}
