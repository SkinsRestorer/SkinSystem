<?php
  require_once(__DIR__ . '/libraries.php');
  session_start();

  if(isset($_GET['logout'])){ session_destroy(); header('Location: ../../'); }

  if(!empty($_POST['username']) && !empty($_POST['password'])){
    $username = strtolower($_POST['username']);
	  $password = $_POST['password'];

    $_SESSION['username'] = $username;
    printDataAndDie();
  }

  printErrorAndDie('Invalid Request!');
?>
