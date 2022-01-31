#!/bin/bash

SQL_PASSWORD=$1

# delete old backups
if test -f /var/www/backup.sql; then
   rm /var/www/backup.sql
fi

# save a copy
/usr/bin/mysqldump -u root -p$SQL_PASSWORD Dead > /var/www/backup.sql 2>&1
