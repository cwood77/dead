#!/bin/bash

SQLPASSWD=$1

if [ -z "$SQLPASSWD" ]; then
   echo "usage: register-all-migrations.sh SQLPASSWD"
   exit 1
fi

/bin/bash /home/vagrant/priv/register-migration.sh 2 "add user prefs and drop unused brokendown column" $SQLPASSWD
/bin/bash /home/vagrant/priv/register-migration.sh 3 "add milestones" $SQLPASSWD
/bin/bash /home/vagrant/priv/register-migration.sh 4 "associate milestones with goals" $SQLPASSWD
/bin/bash /home/vagrant/priv/register-migration.sh 5 "add cancelled step state" $SQLPASSWD
/bin/bash /home/vagrant/priv/register-migration.sh 6 "double string lengths to 100 chars" $SQLPASSWD
/bin/bash /home/vagrant/priv/register-migration.sh 7 "add description field to goals" $SQLPASSWD
