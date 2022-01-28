<?php

function redirect($url)
{
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
}

function demandHttpPost()
{
   if ($_SERVER["REQUEST_METHOD"] != "POST")
   {
      echo "internal messaging error";
      die();
   }
}

function nonEmptyArg($name)
{
   $v = $_REQUEST[$name];
   if (empty($v))
   {
      echo "internal messaging error; arg '$name' absent";
      die();
   }
   return $v;
}

?>
