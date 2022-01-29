<?php

function redirect($url)
{
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
}

class CheckerBase {
   protected $errorMessage;

   function __construct()
   {
      $this->errorMessage = "";
   }

   protected function demandMethod($method)
   {
      if ($_SERVER["REQUEST_METHOD"] != $method)
      {
         $this->addError("HTTP request method must be " . $method);
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

   protected function addError($text)
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

class ApiChecker extends CheckerBase {
   function __construct()
   {
      $this->errorMessage = "";
      $this->demandMethod("POST");
   }
}

class FrontEndChecker extends CheckerBase {
   function __construct()
   {
      $this->errorMessage = "";
      $this->demandMethod("GET");
   }
}

?>
