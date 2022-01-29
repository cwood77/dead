<?php

function includeJsApi($name)
{
   echo '<script src="api.js"></script>';
   echo '<script src="api/' . $name . '.js"></script>';
}

?>
