<?php 

if (!isset($_POST['login']) || !isset($_POST['password'])) {
  header('Location: ../www/index.php');
}

if ($login == $_POST['login'] && $mdp == $_POST['password']) {

  session_start();

  $_SESSION['login']      = strip_tags($_POST['login']);
  $_SESSION['password']   = strip_tags($_POST['password']);
  $_SESSION['codip_id']   = 0;
    header ('location: ../www/index.php');

}
else { header ('location: ../www/index.php'); }

function authentifie () {

  session_start();
  session_regenerate_id();

  if(!isset($_SESSION['login']) || !isset($_SESSION['mdp']))
    return FALSE;

  return TRUE;
}

?>
