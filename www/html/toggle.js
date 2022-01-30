// toggle allows you to open/close panels inside
// the page.
//
// to use:
// - create a var obj to track state
// - wrap the panel in a <div> with the label
//   indicated in the obj
// - call toggle(..,false) in body::onload
// - call toggle in the start and dismissal buttons

function toggle(state, setTo = null)
{
   var v = state.v;
   if (setTo != null)
   {
      v = !setTo;
   }

   if (v)
   {
      // hide
      var items = document.getElementsByName(state.label);
      for(i = 0; i < items.length; i++)
      {
         items[i].style.display = "none";
      }
      var items = document.getElementsByName(state.label + "Start");
      for(i = 0; i < items.length; i++)
      {
         items[i].disabled = false;
      }
      state.v = false;
   }
   else
   {
      // show
      var items = document.getElementsByName(state.label);
      for(i = 0; i < items.length; i++)
      {
         items[i].style.display = "inline";
      }
      var items = document.getElementsByName(state.label + "Start");
      for(i = 0; i < items.length; i++)
      {
         items[i].disabled = true;
      }
      state.v = true;
   }
}
