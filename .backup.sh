#!/bin/bash

# Este script hace una copia de seguridad de una base de datos mysql y la comprime
# en formato gzip en la carpeta especificada

if [ -e ".env" ] && [ ! -z ".env" ]
then
    source .env
fi

USER=$DB_USERNAME
PASSWORD=$DB_PASSWORD
DATABASE=$DB_DATABASE
SAVEDIR=$DB_BACKUP_FOLDER

/usr/bin/nice -n 19 /usr/bin/mysqldump -u $USER --password=$PASSWORD --default-character-set=utf8 $DATABASE -c | /usr/bin/nice -n 19 /bin/gzip -9 > $SAVEDIR/$(date '+%Y-%m-%d_%H-%M-%S_')$DATABASE.sql.gz