api.login = (user, pass, passFunc, errFunc = null) =>
{
   var msg = new ServerMessage("api/login.php",[passFunc,errFunc]);
   msg.run({username:user,password:pass});
}
