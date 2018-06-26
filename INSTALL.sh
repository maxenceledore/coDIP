#!/usr/bin/env bash

echo "coDIP DB v0.05 installer"

# TODO: Check for mariaDB/mysql install

install_codip_db() {
   echo "install_codip_db"
   mysql -u root codip < db/codip_db_setup.sql
}

if [ "$(id -u)" != "0" ]; then
   echo "You must be root to run the installation process."
   exit 1
fi

# internal_ops_pw="internal_ops_pw = \"$(date | md5sum)\""
# echo $internal_ops_pw >> codip.conf

if [ -d /var/lib/mysql/codip ] ; then
   echo "It seems that coDIP has already been installed."
   echo "Should the preview data be overwritten ? (y/n)"
   read override
   if [ "$override" = "y" ] ; then
      echo "Overriding preview install..."
      install_codip_db
   fi
else
    mysqladmin create codip
    install_codip_db
fi

chown -R www-data:www-data www/min
chown -R www-data:www-data www/photos
#chown www-data:www-data codip.conf


