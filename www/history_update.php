<?php
include('../internal/history.php');

// if (!isset($_GET['cmd']) ||
//     !isset($_GET['params']) ||
//     !isset($_GET['uid']) ||
//     !isset($_GET['date']))
//     exit();
// 
$command      = $_GET['cmd'];
$params       = $_GET['params'];
$user_origin  = $_GET['uid'];
$date         = $_GET['date'];

// add_command_history_entry('6fdc56bf803e_jpg_history',
//                           'SATURATION', '+17',
//                           'mledore', '0');

add_command_history_entry('6fdc56bf803e_jpg_history',
                          $command, $params,
                          $user_origin, $date);

exit();

?>
