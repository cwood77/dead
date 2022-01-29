api.addUser = (user, pass, passFunc, errFunc = null) =>
{
   var msg = new ServerMessage("api/addUser.php",[passFunc,errFunc]);
   msg.run({username:user,password:pass});
}
