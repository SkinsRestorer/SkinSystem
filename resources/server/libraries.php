<?php
  $config = require_once(__DIR__ . '../../../config.nogit.php');
  global $config;

  function printDataAndDie($data = []){
    if(!isset($data['success'])){ $data['success'] = empty($data['error']); }
    die(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
  }

  function printErrorAndDie($error){ printDataAndDie(['error' => $error]); }

  function logout(){
    session_start();
    session_destroy();
    die(header('Location: ../'));
  }
?>
