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

   function setTitle($value)
   {
      $sql = "UPDATE Dead.Goals SET title = '" . $value . "' WHERE id = '" . $this->id . "'";
      $this->db->conn->exec($sql);
      $this->title = $value;
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

   function addHistory($username, $text, $kind = "auto")
   {
      $userId = $this->db->findUser($username)->id;
      $sql = "INSERT INTO Dead.GoalHistory (goalID, kind, userID, text) VALUES ("
         . "'" . $this->id . "', "
         . "'" . $kind . "', "
         . "'" . $userId . "', "
         . "'" . $text . "'"
         .  ")";
      $this->db->conn->exec($sql);
   }

   function delete()
   {
      $sql = "DELETE FROM Dead.Goals WHERE id = '" . $this->id . "'";
      $this->db->conn->exec($sql);
   }

   function listHistory()
   {
      $query = $this->db->conn->prepare(
         "SELECT ts, userID, text FROM Dead.GoalHistory WHERE goalID = '" . $this->id . "' ORDER BY ts DESC");
      $query->execute();

      $ans = array();

      $pdoRows = $query->fetchAll();
      foreach($pdoRows as $pdoRow)
      {
         $subquery = $this->db->conn->prepare(
            "SELECT userName FROM Dead.Users WHERE id = '" . $pdoRow['userID'] . "'");
         $subquery->execute();
         $username = $subquery->fetchColumn();

         array_push($ans,
            array(
               $pdoRow['ts'],
               $username,
               $pdoRow['text']));
      }

      return $ans;
   }
}

class User {
   private $db;
   public $id;

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

   function getSharingInfo()
   {
      $query = $this->db->conn->prepare(
         "SELECT looker FROM Dead.GoalsVisibleToUser WHERE lookee = '" . $this->id . "'");
      $query->execute();
      $pdoData = $query->fetchAll();

      $rval = array();
      foreach($pdoData as $row)
      {
          $lookerId = $row['looker'];
          $subquery = $this->db->conn->prepare(
             "SELECT userName FROM Dead.Users WHERE id = '" . $lookerId . "'");
          $subquery->execute();
          $lookerName = $subquery->fetchColumn();

          $rval[$lookerName] = true;
      }

      return $rval;
   }

   function setSharing($looker, $allowed)
   {
      $lookerId = $this->db->findUser($looker)->id;

      $query = $this->db->conn->prepare(
         "SELECT looker FROM Dead.GoalsVisibleToUser WHERE looker = '" . $lookerId . "' AND lookee = '" . $this->id . "'");
      $query->execute();
      if ($query->fetchColumn() == false)
      {
         // not currently enabled
         if ($allowed)
         {
            $sql = "INSERT INTO Dead.GoalsVisibleToUser (looker, lookee) VALUES ('" . $lookerId . "', '" . $this->id . "')";
            $this->db->conn->exec($sql);
         }
         else
         {
            // noop
         }
      }
      else
      {
         // currently enabled
         if ($allowed)
         {
            // noop
         }
         else
         {
            $sql = "DELETE FROM Dead.GoalsVisibleToUser WHERE looker = '" . $lookerId . "' AND lookee = '" . $this->id . "'";
            $this->db->conn->exec($sql);
         }
      }
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
         "SELECT userName, superuser FROM Dead.Users ORDER BY userName ASC");
      $query->execute();
      return $query->fetchAll();
   }

   // ------ migration APIs -------

   function getVersion()
   {
      $query = $this->conn->prepare(
         "SELECT version FROM Dead.Version");
      $query->execute();
      return $query->fetchColumn();
   }

   function listVersions()
   {
      $query = $this->conn->prepare(
         "SELECT version, title FROM Dead.Migration ORDER BY version ASC");
      $query->execute();
      return $query->fetchAll();
   }

   function setVersion($version)
   {
      $query = $this->conn->prepare(
         "UPDATE Dead.Version SET version = " . $version);
      $query->execute();
   }

   // ------ step APIs -------

   function goalHasSteps($goalID)
   {
      $query = $this->conn->prepare(
         "SELECT id FROM Dead.Steps WHERE goalID = '" . $goalID . "'");
      $query->execute();
      return $query->fetchColumn() != false;
   }

   function addStep($goalID)
   {
      $sql = "INSERT INTO Dead.Steps (goalID, title, priority, state) VALUES ('" . $goalID . "', '--New Step--', '3', 'ready')";
      $this->conn->exec($sql);
   }

   function querySteps($goalID)
   {
      $query = $this->conn->prepare(
         "SELECT id, state, priority, title FROM Dead.Steps WHERE goalID = '" . $goalID . "' ORDER BY state ASC, priority ASC, title ASC");
      $query->execute();
      return $query->fetchAll();
   }

   function deleteStep($stepID)
   {
      $sql = "DELETE FROM Dead.Steps WHERE id = '" . $stepID . "'";
      $this->conn->exec($sql);
   }

   function modifyStep($stepID, $state, $priority, $title)
   {
      $sql = "UPDATE Dead.Steps SET state = '" . $state . "', priority = '" . $priority . "', title = '" . $title . "' WHERE id = '" . $stepID . "'";
      $this->conn->exec($sql);
   }
}

?>
