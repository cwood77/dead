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

class ApiChecker {
   private $errorMessage;

   function __construct()
   {
      $this->errorMessage = "";
      $this->demandPost();
   }

   function demandPost()
   {
      if ($_SERVER["REQUEST_METHOD"] != "POST")
      {
         $this->addError("HTTP request method must be POST");
      }
   }

   function demandArg($name)
   {
      $v = $_REQUEST[$name];
      if (empty($v))
      {
         $this->addError("required arg '$name' absent");
      }
      return $v;
   }

   function addError($text)
   {
      if (!empty($this->errorMessage))
      {
         $this->errorMessage .= "; ";
      }
      $this->errorMessage .= $text;
   }

   function check()
   {
      if (!empty($this->errorMessage))
      {
         echo $this->errorMessage;
         die();
      }
   }
}

?>
