<?php

function
add_command_history_entry(/*$connection,*/ $history_table_name,
                          $command, $parameters,
                          $user_origin, $command_date) {

  $codip_connect = new mysqli('localhost', 'codipadmin', 'password', 'codip');

  $update_history_stmt = $codip_connect->prepare(
    "INSERT INTO $history_table_name ".
    "(command, parameters, user_origin, command_date) ".
    "VALUES (?, ?, ?, ?)"
    );
  $update_history_stmt->bind_param('ssss', $command, $parameters,
                                   $user_origin, $command_date);
  $update_history_stmt->execute();
//  echo $codip_connect->affected_rows;
  $update_history_stmt->close();

  $codip_connect->close();
}

?>
