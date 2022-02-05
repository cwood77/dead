// a more flexible API for dealing with preferences

api.setFlexPrefs = (json, passFunc = null, errFunc = null) =>
{
   var msg = new ServerMessage("api/flexPrefs.php",[passFunc,errFunc]);
   msg.run({Op:"set",JsonStr:JSON.stringify(json)});
}

api.getFlexPrefs = (json, passFunc = null, errFunc = null) =>
{
   var wrappedGood = function(json)
   {
      _flexPrefsCallbackWrapper(json,passFunc,errFunc);
   }

   var msg = new ServerMessage("api/flexPrefs.php",[wrappedGood,errFunc]);
   msg.run({Op:"get",JsonStr:JSON.stringify(json)});
}

function _flexPrefsCallbackWrapper(json, realGoodFunc, realErrorFunc)
{
   try
   {
      var obj = JSON.parse(json['jsonStr']);

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
