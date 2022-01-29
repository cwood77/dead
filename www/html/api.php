<?php

function includeJsApi($name)
{
   echo '<script src="api.js"></script>';
   echo '<script src="api/' . $name . '.js"></script>';
}

function includeJsApis($names)
{
   echo '<script src="api.js"></script>';

   foreach($names as $name)
   {
      echo '<script src="api/' . $name . '.js"></script>';
   }
}

?>
