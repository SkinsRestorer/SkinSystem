<?php
  $config = require_once(__DIR__ . '../../../config.nogit.php');
  global $config;

  /* Initialize PDO */
  $authmePDOinstance = new PDO('mysql:host=' . $config['authme']['host'] . '; port=' . $config['authme']['port'] . '; dbname=' . $config['authme']['database'] . ';', $config['authme']['username'], $config['authme']['password']);
	$skinsystemPDOinstance = new PDO('mysql:host=' . $config['sr']['host'] . '; port=' . $config['sr']['port'] . '; dbname=' . $config['sr']['database'] . ';', $config['sr']['username'], $config['sr']['password']);
  $authmePDOinstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$skinsystemPDOinstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  /* When working with Authme or SkinSystem storeage */
  /* $type; 1 = Authme , 2 = SkinSystem */
	function query($type, $mysqlcommand, $key = []){
    if($type == 1){
      global $authmePDOinstance;
      $result = $authmePDOinstance->prepare($mysqlcommand);
      $result->execute($key);
    } else if($type == 2){
      global $skinsystemPDOinstance;
      $result = $skinsystemPDOinstance->prepare($mysqlcommand);
      $result->execute($key);
    }
    return $result;
	}

  function printDataAndDie($data = []){
    if(!isset($data['success'])){ $data['success'] = empty($data['error']); }
    die(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
  }

  function printErrorAndDie($error){ printDataAndDie(['error' => $error]); }

  /* GitHub getLastestVersion */
  function getLatestVersion(){
    global $response;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/repos/riflowth/SkinSystem/releases/latest');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'The SkinSystem');
    $response = curl_exec($ch);
    curl_close($ch);
    if($response === false){
      $response = 'cURL ERROR : ' . curl_error($ch);
    } else {
      $response = json_decode($response, true);
      $response = $response['tag_name'];
    }

    return $response;
  }
?>
