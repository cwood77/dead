// stashes arbitrary JSON on a key in the PHP session

api.setStash = (key, json, passFunc = null, errFunc = null) =>
{
   var msg = new ServerMessage("api/stash.php",[passFunc,errFunc]);
   msg.run({Op:"set",Key:key,JsonStr:JSON.stringify(json)});
}

api.getStash = (key, passFunc = null, errFunc = null) =>
{
   var wrappedGood = function(json)
   {
      _stashCallbackWrapper(json,passFunc,errFunc);
   }

   var msg = new ServerMessage("api/stash.php",[wrappedGood,errFunc]);
   msg.run({Op:"get",Key:key});
}

function _stashCallbackWrapper(json, realGoodFunc, realErrorFunc)
{
   try
   {
      var obj = null;
      var ans = json['jsonStr'];
      if (ans != '')
      {
         obj = JSON.parse(ans);
      }

      if (realGoodFunc == null)
      {
         return;
      }
      realGoodFunc(obj);
   }
   catch(e)
   {
      var func = realErrorFunc;
      if (func == null)
      {
         func = alert;
      }
      func(value);
   }
}
