<?php

require '/var/www/db-secrets.php';

class Goal {
   private $db;
   private $id;
   private $title;
   private $pri;

   function __construct($db, $id, $title, $pri)
   {
      $this->db = $db;
      $this->id = $id;
      $this->title = $title;
      $this->pri = $pri;
   }

   function getTitle()
   {
      return $this->title;
   }

   function setTitle()
   {
   }

   function getPriority()
   {
      return $this->pri;
   }

   function setPriority($value)
   {
      $sql = "UPDATE Dead.Goals SET priority = '" . $value . "' WHERE id = '" . $this->id . "'";
      $this->db->conn->exec($sql);
      $this->pri = $value;
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

      return new Goal($this->db, $gid, $title, 4);
   }

   function listGoals()
   {
      $query = $this->db->conn->prepare(
         "SELECT id, priority, title FROM Dead.Goals WHERE userID = '" . $this->id . "' ORDER BY priority ASC, title ASC");
      $query->execute();
      return $query->fetchAll();
   }

   function queryGoal($gid)
   {
      $query = $this->db->conn->prepare(
         "SELECT priority, title FROM Dead.Goals WHERE id = '" . $gid . "'");
      $query->execute();
      $fields = $query->fetchAll();
      return new Goal($this->db, $gid, $fields[0]['title'], $fields[0]['priority']);
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

   function listUsers()
   {
      $query = $this->conn->prepare(
         "SELECT userName FROM Dead.Users ORDER BY userName ASC");
      $query->execute();
      return $query->fetchAll();
   }
}

?>
