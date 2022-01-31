<?php

require '/var/www/db-secrets.php';

// get prefs from username - add/edit/acct usecase

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
   private $su;

   function __construct($db, $id, $su)
   {
      $this->db = $db;
      $this->id = $id;
      $this->su = $su;
   }

   function isSuper()
   {
      return $this->su;
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

   function listGoals($all)
   {
      $queryText = "SELECT userName, Dead.Goals.id, priority, title FROM Dead.Goals LEFT JOIN Dead.Users ON UserID = Dead.Users.id";
      if (!$this->su)
      {
         $queryText .= " WHERE userID = '" . $this->id . "'";
         if ($all)
         {
            $queryText .= $this->_buildQueryForUsersSharedWithMe();
         }
      }
      $queryText .= " ORDER BY priority ASC, title ASC";
      $query = $this->db->conn->prepare($queryText);
      $query->execute();
      return $query->fetchAll();
   }

   function _buildQueryForUsersSharedWithMe()
   {
      $query = $this->db->conn->prepare(
         "SELECT lookee FROM Dead.GoalsVisibleToUser WHERE looker = '" . $this->id . "'");
      $query->execute();
      $rows = $query->fetchAll();

      $html = "";
      foreach($rows as $row)
      {
         $html .= " OR userID = '" . $row[0] . "'";
      }

      return $html;
   }

   function listSteps($all)
   {
      $queryText =
         "SELECT Dead.Steps.title, Dead.Steps.priority, state, Dead.Goals.title, Dead.Steps.GoalID, userName FROM Dead.Steps "
         . "LEFT JOIN Dead.Goals ON Dead.Steps.goalID = Dead.Goals.id "
         . "LEFT JOIN Dead.Users ON Dead.Goals.userID = Dead.Users.id";
      if (!$this->su)
      {
         $queryText .= " WHERE Dead.Goals.UserID = '" . $this->id . "'";
         if ($all)
         {
            $queryText .= $this->_buildQueryForUsersSharedWithMe();
         }
      }
      $queryText .= " ORDER BY state ASC, Dead.Steps.priority DESC, Dead.Steps.title ASC";
      $query = $this->db->conn->prepare($queryText);
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
         $query = $this->conn->prepare("SELECT id, superuser FROM Users WHERE userName='" . $username . "'");
         $query->execute();
         $rows = $query->fetchAll();
         if (!empty($rows))
         {
            return new User($this,$rows[0][0],$rows[0][1]);
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

      $query = $this->conn->prepare("SELECT LAST_INSERT_ID();");
      $query->execute();
      $userId = $query->fetchColumn();

      $sql = "INSERT INTO UserPrefs (id) VALUES ('" . $userId . "')";
      $this->conn->exec($sql);
   }

   function listUsers()
   {
      $query = $this->conn->prepare(
         "SELECT id, userName, superuser FROM Dead.Users ORDER BY userName ASC");
      $query->execute();
      return $query->fetchAll();
   }

   function modUser($userId, $op)
   {
      if ($op == "s")
      {
         // toggle superuser
         $query = $this->conn->prepare(
            "SELECT superuser FROM Dead.Users WHERE id = " . $userId);
         $query->execute();
         $su = $query->fetchColumn();
         if($su)
         {
            $su = 0;
         }
         else
         {
            $su = 1;
         }
         $query = $this->conn->prepare(
            "UPDATE Dead.Users SET superuser = " . $su . " WHERE id = '" . $userId . "'");
         $query->execute();
      }
      else if($op == "f")
      {
         $query = $this->conn->prepare(
            "SELECT userName FROM Dead.Users WHERE id = " . $userId);
         $query->execute();
         $name = $query->fetchColumn();

         $query = $this->conn->prepare(
            "UPDATE Dead.Users SET password = '" . password_hash($name, PASSWORD_DEFAULT) . "' WHERE id = '" . $userId . "'");
         $query->execute();
      }
      else if($op == "d")
      {
         $sql = "DELETE FROM Dead.Users WHERE id = '" . $userId . "'";
         $this->conn->exec($sql);
      }
      else
      {
         echo "not implemented";
      }
   }

   function setUserPrefs($username, $defGoalPri, $defStepPri, $defStepState)
   {
      $sql = "UPDATE Dead.UserPrefs LEFT JOIN Dead.Users ON Dead.Users.id = Dead.UserPrefs.id  SET goalPriority = '" . $defGoalPri . "', stepPriority = '" . $defStepPri . "', stepState = '" . $defStepState . "' WHERE userName = '" . $username . "'";
      $this->conn->exec($sql);
   }

   function getUserPrefs($username)
   {
      $query = $this->conn->prepare(
         "SELECT goalPriority, stepPriority, stepState FROM Dead.UserPrefs LEFT JOIN Dead.Users ON Dead.UserPrefs.id = Dead.Users.id WHERE userName = '" . $username . "'");
      $query->execute();
      return $query->fetchAll()[0];
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

   function getGoalStepState($goalID)
   {
      $query = $this->conn->prepare(
         "SELECT state FROM Dead.Steps LEFT JOIN Dead.Goals ON Dead.Steps.goalID = Dead.Goals.id WHERE Dead.Steps.goalID = '" . $goalID . "' ORDER BY state ASC LIMIT 1");
      $query->execute();
      $rows = $query->fetchAll();
      if (empty($rows))
      {
         return "nosteps";
      }
      return $rows[0][0];
   }

   function addStep($username, $goalID)
   {
      $prefs = $this->getUserPrefs($username);

      $sql = "INSERT INTO Dead.Steps (goalID, title, priority, state) VALUES ('"
         . $goalID . "', '--New Step--', '" . $prefs['stepPriority'] . "', '" . $prefs['stepState'] . "')";
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
