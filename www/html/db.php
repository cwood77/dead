<?php

require '/var/www/db-secrets.php';

class User {
   private $id;

   function __construct($db, $id)
   {
   }

}

class Db {
   private $conn;

   function __construct()
   {
      $this->conn = new PDO("mysql:host=localhost;dbname=Dead", DBUSER, DBPASSWD);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   }

   function __destruct()
   {
      $this->conn = null;
   }

   function findUser($username)
   {
      try
      {
         $query = $this->conn->prepare("SELECT id FROM Users WHERE userName='" . $username . "'");
         $query->execute();
         $id = $query->fetchColumn();
         if ($id != false)
         {
            return new User($this,$id);
         }
      }
      catch(PDOException $x)
      {
         echo $x->getMessage();
      }
      return null;
   }

   function addUser($username, $password)
   {
      try
      {
         $sql = "INSERT INTO Users (displayName, password, userName) VALUES ('" . $username . "', '" . $password . "', '" . $username . "')";
         $this->conn->exec($sql);
      }
      catch(PDOException $x)
      {
         return $x->getMessage();
      }
   }
}

?>
