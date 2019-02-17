<?php
  require_once(__DIR__ . '/libraries.php');

  if(!empty($_POST['username']) && !empty($_POST['isSlim']) && !empty($_POST['uploadtype'])){
    printDataAndDie();
  }

  printErrorAndDie('Invalid Request!');
 ?>
