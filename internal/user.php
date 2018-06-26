<?php

include_once('collection.php');
include_once('image.php');

function add_user($surname, $first_name, $password) {

  $surname    = strip_tags($surname);
  $first_name = strip_tags($first_name);
  $password   = strip_tags($password);

  $samples = "";

  // TODO: only allow ASCII alpha chars.
  $login = $first_name[0].$surname;
  $login = strtolower($login);
  $login = str_replace(" ", "", $login);

  $passwd_hash            = password_hash($password, PASSWORD_DEFAULT);
  $phototeque_table_name  = $login.'_phototeque';

  $codip_connect = new mysqli('localhost', 'codipadmin', 'password', 'codip');


  $add_user_stmt = $codip_connect->prepare(
    "INSERT INTO codip_users ".
    "(surname, first_name, login, passwd_hash, ".
    "phototeque_table_name, public_profile)".
    "VALUES ( ?, ?, ?, ?, ?, FALSE)"
    );
  $add_user_stmt->bind_param('sssss', $surname, $first_name, $login,
                             $passwd_hash, $phototeque_table_name);
  $add_user_stmt->execute();
  $add_user_stmt->close();


  // Prepared request only allowed with DML statements, not on DDL ones
  $create_phototeque_table_name_query =
  "CREATE TABLE $phototeque_table_name (".
  "id                        INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,".
  "collection_table_name     VARCHAR(128) NOT NULL,".
  "collection_name           VARCHAR(128) NOT NULL)".
  "CHARACTER SET=utf8";
  $create_phototeque_table_name_query =
  $codip_connect->real_escape_string($create_phototeque_table_name_query);
  $codip_connect->query($create_phototeque_table_name_query);


  create_collection($codip_connect, $login, $phototeque_table_name,
                    'Samples');

  add_image_to_collection($codip_connect, $login.'_'.'samples',
                          '6fdc56bf803e.jpg');
  add_image_to_collection($codip_connect, $login.'_'.'samples',
                          '9cb35ed71248.jpg');

  $codip_connect->close();
}

add_user('Le Dore', 'Maxence', 'password');

?>
