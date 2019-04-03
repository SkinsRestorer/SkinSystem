<?php
  require_once(__DIR__ . '/libraries.php');
  if($config['am']['enabled'] == false){ printErrorAndDie('Unusable system!'); }
  session_start();

  /* logout Request */
  if(isset($_GET['logout'])){ session_destroy(); header('Location: ../../'); }

  /* If login request is valid */
  if(!empty($_POST['username']) && !empty($_POST['password'])){
    $username = strtolower($_POST['username']);
    $timeout = $config['am']['authsec']['threshold_hours']*60*60;
    $cdir = '../../'.$config['cache_dir']; if (!is_dir($cdir)) {mkdir($cdir, 0775, true);}
    // rate limit by publicly assigned ip (prefix v6, whole v4)
    preg_match('/^(?:\w+[:.]){0,3}\w+/', $_SERVER['REMOTE_ADDR'], $addr);
    $blk[0] = $cdir.'.loginratelimit-addr-'.preg_replace('/[^ \w]+/', '-', $addr[0]);
    // rate limit by username they are trying to log into (limit+1, limit IP before username)
    $blk[1] = $cdir.'.loginratelimit-user-'.preg_replace('/[^ \w]+/', '-', $username);
    $now = time();
    if (max([filemtime($blk[0]), filemtime($blk[1])]) < $now) {
      $password = $_POST['password'];
      /* Get user's password from AuthMe Storage */
      $pdo = query('am', 'SELECT password FROM authme WHERE username = ?', [$username]);
      /* Analyse AuthMe Password Algorithm */
      $hashParts = explode('$', $pdo->fetch(PDO::FETCH_ASSOC)['password']);
      if(count($hashParts) == 4 && hash('sha256',  hash('sha256', $password) . $hashParts[2]) == $hashParts[3]){
        $_SESSION['username'] = $username;
        foreach ($blk as $rlfl) {if (is_file($rlfl)) { unlink($rlfl); }}
        printDataAndDie();
      } else {
        /* Login failed, they should stop soon! */
        foreach ($blk as $index => $rlfl) {
          $failvl = filemtime($rlfl);
          if (($failvl < ($now - $timeout)) or (!is_file($rlfl))) {$failvl = ($now - $timeout);} 
          $failvl = ($failvl + ($timeout/($config['am']['authsec']['failed_attempts']+$index)) + 120);
          touch($rlfl, $failvl);
        }
        printErrorAndDie(['code' => 401, 'data' => 'Please check your username or password']);
      }
    } else {
      printErrorAndDie(['code' => 429, 'data' => 'Please come back later']);
    }
  }

  printErrorAndDie('Invalid Request!');
?>
