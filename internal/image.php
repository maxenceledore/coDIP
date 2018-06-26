<?php

function add_image_to_collection($connection, $collection_table_name,
                                 $filename) {

  // TODO: only allow ASCII alpha chars.
  $timestamp = time();
  $history_table_name  = str_replace(" ", "_", $filename);
  $snapshots_table_name = str_replace(" ", "_", $filename);

  $history_table_name  = str_replace(".", "_", $history_table_name);
  $snapshots_table_name = str_replace(".", "_", $snapshots_table_name);

  $history_table_name  .= '_history';
  $snapshots_table_name .= '_snapshots';

  $add_image_stmt = $connection->prepare(
    "INSERT INTO $collection_table_name ".
    "(filename, upload_date, history_table_name, snapshot_table_name) ".
    "VALUES (?, ?, ?, ?)"
    );
  $add_image_stmt->bind_param('ssss', $filename, $timestamp,
                             $history_table_name, $snapshots_table_name);
  $add_image_stmt->execute();
  $add_image_stmt->close();

  // Create history table.
  // Prepared request only allowed with DML statements, not on DDL ones
  $create_history_table_query =
  "CREATE TABLE $history_table_name (".
  "id                  INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ".
  "command             VARCHAR(64)  NOT NULL, ".
  "parameters          VARCHAR(128) NOT NULL, ".
  "user_origin         VARCHAR(64) NOT NULL, ".
  "command_date        TIMESTAMP".
  ") CHARACTER SET=utf8";

  $create_history_table_query =
  $connection->real_escape_string($create_history_table_query);
  $connection->query($create_history_table_query);


  // Create snapshots table.
  // Prepared request only allowed with DML statements, not on DDL ones
  $create_snapshots_table_query =
  "CREATE TABLE $snapshots_table_name (".
  "id                  INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ".
  "filename            VARCHAR(64) NOT NULL, ".
  "user_origin         VARCHAR(64) NOT NULL, ".
  "command_date        TIMESTAMP, ".
  "history_index       INT UNSIGNED".
  ") CHARACTER SET=utf8";

  $create_snapshots_table_query =
  $connection->real_escape_string($create_snapshots_table_query);
  $connection->query($create_snapshots_table_query);
}

?>
