<?php

require '/var/www/db-secrets.php';

class Goal {
   private $db;
   private $id;

   function __construct($db, $id)
   {
      $this->db = $db;
      $this->id = $id;
   }

   function getTitle()
   {
   }

   function setTitle()
   {
   }

   function getPriority()
   {
   }

   function setPriority($value)
   {
      $sql = "UPDATE Dead.Goals SET priority = '" . $value . "' WHERE id = '" . $this->id . "'";
      $this->db->conn->exec($sql);
   }
}

class User {
   private $db;
   private $id;

   function __construct($db, $id)
   {
      $this->db = $db;
      $this->id = $id;
   }

   function login($password)
   {
      $query = $this->db->conn->prepare("SELECT password FROM Users WHERE id='" . $this->id . "'");
      $query->execute();
      $expected = $query->fetchColumn();
      if (password_verify($password,$expected))
      {
         return true;
      }
      return false;
   }

   function addGoal($title)
   {
      $sql = "INSERT INTO Goals (userID, title) VALUES ('" . $this->id . "', '" . $title . "')";
      $this->db->conn->exec($sql);

      $query = $this->db->conn->prepare("SELECT LAST_INSERT_ID();");
      $query->execute();
      $gid = $query->fetchColumn();

      return new Goal($this->db, $gid);
   }

   function listGoals()
   {
      $query = $this->db->conn->prepare(
         "SELECT priority, title FROM Dead.Goals WHERE userID = '" . $this->id . "' ORDER BY priority ASC, title ASC");
      $query->execute();
      return $query->fetchAll();
   }
}

class Db {
   public $conn;

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
      $sql = "INSERT INTO Users (password, userName) VALUES ('" . $password . "', '" . $username . "')";
      $this->conn->exec($sql);
   }
}

?>
