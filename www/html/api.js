class ServerMessage {
   constructor(url, callbacks)
   {
      this.url = url;
      this.callbacks = callbacks;
   }

   run(args)
   {
      var msg = this;
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
            msg._callbackWrapper(this.responseText);
         }
      };


      var fullUrl = this.url;
      var first = true;
      for(var key in args)
      {
         var val = args[key];
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

   _callbackWrapper(value)
   {
      try
      {
         var obj = JSON.parse(value);

         var func = this.callbacks[0];
         if (func == null)
         {
            return;
         }
         func(obj);
      }
      catch(e)
      {
         var func = this.callbacks[1];
         if (func == null)
         {
            func = alert;
         }
         func(value);
      }
   }
}

const api = {}
