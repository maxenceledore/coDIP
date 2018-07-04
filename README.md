
Dependencies :

 - Apache/nginx
 - PHP
 - PHP-GD extension
 - MySQL/MariaDB

Only JPEG import ATM.

Installation :
 - Virtual Host setup
 - cd coDIP/
 - sudo chown -R www-data:www-data min/
 - sudo chown -R www-data:www-data photos/

PHP default settings set maximum upload file size to 2MB. This is far to low to import most images. Larger image import will fail.

The PHP Apache module configuration file in Debian based install is usually located at :
/etc/php/7.x/apache2/php.ini (where 7.x is the installed PHP version)

Find the __upload_max_filesize__ parameter and modify it to something like :

__upload_max_filesize = 40M__

It should be enough.

Supported OS : GNU/Linux
