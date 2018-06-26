<?php

function
create_collection($connection, $user_login, $phototeque_table_name,
                  $collection_name) {

  // TODO: only allow ASCII alpha chars.
  $collection_name = strtolower($collection_name);
  $collection_name = str_replace(" ", "_", $collection_name);

  $collection_table_name = $user_login.'_'.$collection_name;

  // Prepared request only allowed with DML statements, not on DDL ones
  $create_collection_table_query =
  "CREATE TABLE $collection_table_name (".
  "id                  INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,".
  "filename            VARCHAR(64) NOT NULL,".
  "upload_date         TIMESTAMP,".
  "history_table_name  VARCHAR(64) NOT NULL,".
  "snapshot_table_name VARCHAR(64) NOT NULL".
  ") CHARACTER SET=utf8";

  $create_collection_table_query =
  $connection->real_escape_string($create_collection_table_query);
  $connection->query($create_collection_table_query);


  // register collection table query
  $register_collection_stmt = $connection->prepare(
    "INSERT INTO $phototeque_table_name ".
    "(collection_table_name, collection_name) VALUES (?, ?)"
    );
  $register_collection_stmt->bind_param('ss', $collection_table_name,
                                        $collection_name);
  $register_collection_stmt->execute();
  $register_collection_stmt->close();
}

?>
