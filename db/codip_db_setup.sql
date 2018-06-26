DROP USER IF EXISTS 'codipadmin'@'localhost';
DROP DATABASE IF EXISTS codip;

CREATE USER IF NOT EXISTS 'codipadmin'@'localhost' IDENTIFIED BY 'password';

CREATE DATABASE IF NOT EXISTS codip;

USE codip;

CREATE TABLE IF NOT EXISTS codip_users (
id                     INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
surname                VARCHAR(64) NOT NULL,
first_name             VARCHAR(64) NOT NULL,
login                  VARCHAR(128) NOT NULL,
passwd_hash            VARCHAR(255) NOT NULL,
phototeque_table_name  VARCHAR(128) NOT NULL,
public_profile         BOOLEAN
) CHARACTER SET=utf8;

CREATE TABLE IF NOT EXISTS ip_log (
id              INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
address             VARCHAR(45) NOT NULL, /* [::ffff:192.168.0.1] */
first_fail          TIMESTAMP,
last_fail           TIMESTAMP,
fails_count         INT UNSIGNED
) CHARACTER SET=utf8;

GRANT ALL PRIVILEGES ON codip.* TO 'codipadmin'@'localhost';
FLUSH PRIVILEGES;
