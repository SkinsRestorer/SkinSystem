<?php
  $config = require_once(__DIR__ . '/../../config.nogit.php');
  global $config;
  /* client remote address with ipv6 as (/64 block) prefix */
  define('IP', preg_replace('/^(?:((?:[[:xdigit:]]+(:)){1,4})[[:xdigit:]:]*|((?:\d+\.){3}\d+))$/', '\1\2\3', $_SERVER['REMOTE_ADDR']));
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
  function printErrorAndDie($error){
    $data['error'] = $error;
    $url = 'https://status.mojang.com/check';
    $mjstatus = json_decode(curl(cacheGrab($url,$url,__DIR__.'/../../',60)), true);
    foreach ($mjstatus as $key) {
      foreach ($key as $site => $status) {
        if ($status !== 'green') {
          $data['footer'] = $data['footer']."\n".'<div class="col"><a href="https://help.mojang.com"><i class="fas fa-exclamation-circle" style="padding-right: 5px;"></i>'.$site.' is having issues</a></div>';
          error_log($site.' is having issues (https://help.mojang.com)');
        }
      }
    }
    $url = 'https://status.mineskin.org';
    $dta = curl(cacheGrab($url,$url,__DIR__.'/../../',(60*60)));
    preg_match('/https:\/\/status\.mineskin\.org\/api\/getMonitorList\/\w+/', $dta, $match);
    $ret = curl(cacheGrab($match[0],$match[0],__DIR__.'/../../',60));
    foreach (json_decode($ret, true)['psp']['monitors'] as $value) {
      if ($value['name'] == 'Mineskin API' and $value['statusClass'] != 'success') {
        $expl = explode('/', $value['monitorId']);
        $data['footer'] = $data['footer']."\n".'<div class="col"><a href="https://status.mineskin.org"><i class="fas fa-exclamation-circle" style="padding-right: 5px;"></i>'.$value['name'].' is having issues</a></div>';
        error_log($value['name'].' is having issues (https://status.mineskin.org)');
      }
    }
    if (isset($data['footer'])) { $data['footer'] = '<div class="container">'.$data['footer'].'</div>'; }
    printDataAndDie($data);
  }

  /* GitHub getLastestVersion */
  function getLatestVersion(){
    $nwVer = cacheGrab('https://api.github.com/repos/riflowth/SkinSystem/releases/latest','latest_version',__DIR__.'/../../',(24*60*60));
    return json_decode(curl($nwVer), true)['tag_name'];
  }

  function curl($url){
    if (preg_match('/^https?:\/\//', $url)) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_USERAGENT, 'The SkinSystem');
      $response = curl_exec($ch);
      curl_close($ch);
      if($response === false){
        printErrorAndDie('cURL ERROR : ' . curl_error($ch));
      }
      return($response);
    } else {
      return(file_get_contents($url));
    }
  }

  // downloads to cache if need be, removes unused cache files, return location of cache file.
  function cacheGrab ($url, $nm, $dirhead='', $max_cache=false, $hchk=false) {
    global $config; $cdir = $dirhead.$config['cache_dir']; if (!is_dir($cdir)) {mkdir($cdir, 0775, true);}
    $file = $cdir.preg_replace('/[^\w\.]/', '_', $nm);
    if (!$max_cache) {if (is_file($file)) {touch($file);}} // no max cache? touch so it's not caught by cleanup
    if (($max_cache && is_file($file) && (time() - filemtime($file) >= $max_cache)) || !is_file($file)) {
      $filecont = curl($url);
      if ($hchk) {
        for ($i = 0; $i < 10; $i++) {
          if (hash($hchk[0], $filecont) != $hchk[1]) {
            $filecont = curl($url);
          } else {break;}
        }
      }
      file_put_contents($file, $filecont);
    } cacheClean($dirhead);
    return $file;
  }
  function cacheClean ($dirhead='') { // initiate cleanup only every 15 mins or so
    global $config; $cdir = $dirhead.$config['cache_dir']; if (!is_dir($cdir)) {mkdir($cdir, 0775, true);}
    $now = time();
    if ($now - filemtime($cdir.'index.php') >= (15*60)) {
      touch($cdir.'index.php');
      foreach (glob($cdir.'*') as $cl) {
        if (is_file($cl) && ($now - filemtime($cl) >= $config['cache_for_days']*24*60*60)) {unlink($cl);}
      }
    }
  }
?>
