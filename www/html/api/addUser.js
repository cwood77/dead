function readyFuncWrapper(value, callbacks)
{
   try
   {
      var obj = JSON.parse(value);

      var func = callbacks[0];
      if (func == null)
      {
         return;
      }
      func(obj);
   }
   catch(e)
   {
      var func = callbacks[1];
      if (func == null)
      {
         func = alert;
      }
      func(value);
   }
}

function sendServerMessage(url, dict, callbacks)
{
   var xmlhttp = new XMLHttpRequest();
   xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
         readyFuncWrapper(this.responseText, callbacks);
      }
   };


   var fullUrl = url;
   var first = true;
   for(var key in dict)
   {
      var val = dict[key];
      if (first)
      {
         fullUrl += "?";
         first = false;
      }
      else
      {
         fullUrl += "&";
      }
      fullUrl += key;
      fullUrl += "=";
      fullUrl += val;
   }


   xmlhttp.open(
      "POST",
      fullUrl,
      true);
   xmlhttp.send();
}

function addUser(user, pass, passFunc, errFunc = null)
{
   sendServerMessage("api/addUser.php",{ username: user, password: pass}, [passFunc, errFunc]);
}

const api = {}
api.addUser = (user, pass, passFunc, errFunc = null) =>
{
   var msg = new ServerMessage("api/addUser.php",[passFunc,errFunc]);
   msg.run({username:user,password:pass});
}
