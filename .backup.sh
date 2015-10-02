#!/bin/bash
#Script to make a regular copy of a mysql database and gzip it into the SAVEDIR.

USER="llstarscreamll"
PASSWORD=""
DATABASE="c9"
SAVEDIR="storage/app/db/"

/usr/bin/nice -n 19 /usr/bin/mysqldump -u $USER --password=$PASSWORD --default-character-set=utf8 $DATABASE -c | /usr/bin/nice -n 19 /bin/gzip -9 > $SAVEDIR/$(date '+%Y-%m-%d_%H-%M-%S_')$DATABASE.sql.gz