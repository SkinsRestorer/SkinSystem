<?php
  $config = require_once(__DIR__ . '/../../config.nogit.php');
  global $config;
  /* Initialize PDO */
  if($config['am']['enabled'] == true){
    $amPDOinstance = new PDO('mysql:host=' . $config['am']['host'] . '; port=' . $config['am']['port'] . '; dbname=' . $config['am']['database'] . ';', $config['am']['username'], $config['am']['password']);
    $amPDOinstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  $srPDOinstance = new PDO('mysql:host=' . $config['sr']['host'] . '; port=' . $config['sr']['port'] . '; dbname=' . $config['sr']['database'] . ';', $config['sr']['username'], $config['sr']['password']);
  $srPDOinstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  /* When working with AuthMe or SkinsRestorer database */
	function query($type, $mysqlcommand, $key = []){
    if($type == 'am'){
      global $amPDOinstance;
      $result = $amPDOinstance->prepare($mysqlcommand);
      $result->execute($key);
    } else if($type == 'sr'){
      global $srPDOinstance;
      $result = $srPDOinstance->prepare($mysqlcommand);
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

  // downloads to cache if need be, removes unused cache files, return location of cache file.
  function cacheGrab ($url, $nm, $dirhead='', $max_cache=false) {
    global $config; $cdir = $dirhead.$config['cache_dir']; if (!is_dir($cdir)) {mkdir($cdir, 0775, true);}
    $file = $cdir.$nm; 
    
    if (!$max_cache) {if (is_file($file)) {touch($file);}} // no max cache? touch so it's not caught by cleanup
    if (($max_cache && (time() - filemtime($file) >= $max_cache)) || !is_file($file)) {
      file_put_contents($file, file_get_contents($url));
    } cacheClean($dirhead);
    return $file;
  }
  function cacheClean ($dirhead='') { // initiate cleanup only every 15 mins or so
    global $config; $cdir = $dirhead.$config['cache_dir']; if (!is_dir($cdir)) {mkdir($cdir, 0775, true);}
    $now = time();
    if ($now - filemtime($cdir.'.lastCleanup') >= (15*60)) {
      touch($cdir.'.lastCleanup');
      foreach (glob($cdir.'*') as $cl) {
        if (is_file($cl) && ($now - filemtime($cl) >= $config['cache_for_days']*24*60*60)) {unlink($cl);}
      }
    }
  }
?>
