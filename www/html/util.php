<?php

function redirect($url)
{
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
}

function leaveIfNoSession()
{
   session_start();
   if (empty($_SESSION['username']))
   {
      redirect('index.php');
   }
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
      if (!array_key_exists($name,$_REQUEST))
      {
         $this->addError("required arg '$name' absent");
      }
      return htmlspecialchars_decode($v);
   }

   function demandArgBool($name)
   {
      $v = $this->demandArg($name);
      if ($v == "false")
      {
         return false;
      }
      return true;
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
