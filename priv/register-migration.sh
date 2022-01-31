#!/bin/bash

VERSION=$1
DESC=$2
SQLPASSWD=$3

if [ -z "$VERSION" ]; then
   echo "usage: register-migration.sh VERSION DESC SQLPASSWD"
   exit 1
fi
if [ -z "$DESC" ]; then
   echo "usage: register-migration.sh VERSION DESC SQLPASSWD"
   exit 1
fi
if [ -z "$SQLPASSWD" ]; then
   echo "usage: register-migration.sh VERSION DESC SQLPASSWD"
   exit 1
fi

SCRIPT=/home/vagrant/priv/migrate-to-version-$VERSION.sql
if [ ! -f "$SCRIPT" ]; then
   echo "migration script doesn't exist - $SCRIPT"
   exit 1
fi

echo "adding $VERSION to database..."
mysql -u root -p$SQLPASSWD << _EOF
INSERT INTO Dead.Migration (version, title) VALUES ("$VERSION", "$DESC");
_EOF

echo "done"
