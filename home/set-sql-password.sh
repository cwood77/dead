#!/bin/bash
# vi: set ft=bash :

OLD_PASSWD=$1
NEW_PASSWD=$2

echo generating helper file
sudo cat <<EOF >>/home/vagrant/_set-password.sql
ALTER USER 'root'@'localhost' IDENTIFIED BY '$NEW_PASSWD'
EOF

echo running sql commands
sudo mysql -u root -p $OLD_PASSWD </home/vagrant/_set-password.sql
