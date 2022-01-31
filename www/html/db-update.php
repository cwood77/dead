<?php

require 'db.php';

$db = new Db();
$version = $db->getVersion();

$didBackup = false;
$didWork = false;
$error = 0;
$migrations = $db->listVersions();

echo "currently at version " . $version . "<br/>";

foreach($migrations as $migration)
{
   if (!$didBackup)
   {
      echo "running backup before making changes...</br>";
      $command = "/bin/bash /home/vagrant/priv/db-backup.sh " . DBPASSWD;
      $error = runCommand($command);
      $didBackup = true;
   }

   if ($error != 0)
   {
      echo "Stopping early<br/>";
      break;
   }

   if ($version < $migration['version'])
   {
      echo "<hr>";
      echo "updating to version " . $migration['version'] . " (" . $migration['title'] . ")<br>";
      $script = "/home/vagrant/priv/migrate-to-version-" . $migration['version'] . ".sql";
      echo "running SQL script '" . $script . "'<br/>";

      $command = "mysql -u " . DBUSER . " -p" . DBPASSWD . " < " . $script . " 2>&1";
      $error = runCommand($command);

      $version = $migration['version'];
      $didWork = true;
   }
}

echo "<hr>";
if ($didWork)
{
   if ($error == 0)
   {
      echo "updating DB version";
      $db->setVersion($version);
   }
   else
   {
      echo "NOT updating DB version because of error";
   }
}
else
{
   echo "database already up to date";
}

function runCommand($command)
{
   $output = "";
   $error = 0;
   $lastline = exec($command,$output,$error);
   echo "<font style='background-color:grey'>";
   foreach($output as $line)
   {
      echo $line . "<br/>";
   }
   echo "</font>";
   echo "rval=" . $error . "</br>";
   return $error;
}

?>
