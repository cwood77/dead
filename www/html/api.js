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
         fullUrl += this._encode(key);
         fullUrl += "=";
         fullUrl += this._encode(val);
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

   _encode(thing)
   {
      // make sure str is a string
      var str = "" + thing;

      var map = {
         ' ' : '&#32;',
         '\t': '&#09;',
         '\r': '&#12;',
         '\n': '&#13;',
      };

      var multilineEncoding = str.replace(/[ \t\r\n]/g, function(m) { return map[m]; });
      return encodeURIComponent(multilineEncoding);
   }

   _jsHtmlSpecialChars(thing)
   {
      // make sure str is a string
      var str = "" + thing;

      var map = {
         '&': '&amp;',
         '<': '&lt;',
         '>': '&gt;',
         '"': '&quot;',
         "'": '&#039;'
      };

      return str.replace(/[&<>"']/g, function(m) { return map[m]; });
   }
}

const api = {}

function checkIfEmpty(name)
{
   var e = document.getElementById(name);
   if (e.value.length == 0)
   {
      return name + " cannot be empty<br/>";
   }
   return "";
}
