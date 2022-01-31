// op = 'toggleSuper', 'forgot', or 'delete'
// pass userId = "." to do a noop
// returns HTML of all users
api.modUser = (userId, op, passFunc, errFunc = null) =>
{
   var msg = new ServerMessage("api/modUser.php",[passFunc,errFunc]);
   msg.run({UserID:userId,Op:op});
}
