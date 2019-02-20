<?php
  require_once(__DIR__ . '/libraries.php');
  session_start();

  /* logout Request */
  if(isset($_GET['logout'])){ session_destroy(); header('Location: ../../'); }

  /* If login request is valid */
  if(!empty($_POST['username']) && !empty($_POST['password'])){
    $username = strtolower($_POST['username']);
	  $password = $_POST['password'];

    /* Get user's password from Authme Storage */
    $pdo = query(1, "SELECT password FROM {$config['authme']['table']} WHERE username = ?", [$username]);
    $result = $pdo->fetch(PDO::FETCH_ASSOC)['password'];
    /* Analyse Authme Password Alogorithm */
    $hashParts = explode('$', $result);
    if(count($hashParts) == 4 && hash('sha256',  hash('sha256', $password) . $hashParts[2]) == $hashParts[3]){
      $_SESSION['username'] = $username;
      printDataAndDie();
    } else {
      printErrorAndDie('Password is invalid!');
    }
  }

  printErrorAndDie('Invalid Request!');
?>
