<?php
  require_once(__DIR__ . '/../resources/server/libraries.php');
  /* Update config file */
  function confupdater($config, $version){
    $config['version'] = $version;
    $renames = [
      'playretable' => 'playertable'
    ];
    // default config file layout, with default values
    preg_match('/([^\/]*)\.css$/', glob(__DIR__ . '/../resources/themes/*.css')[0], $thm);
    $defaults = [
      'version' => false,
      'def_theme' => $thm[1],
      'am' => [
        'enabled' => false,
        'host' => '',
        'port' => '',
        'database' => '',
        'username' => '',
        'password' => '',
        'authsec' => [
          'enabled' => true,
          'failed_attempts' => 3,
          'threshold_hours' => 24
        ]
      ],
      'sr' => [],
      'cache_for_days' => 7,
      'cache_dir' => 'resources/cache/'
    ];
    foreach ($renames as $from => $to) {
      if (isset($config[$from])) {
        $config[$to] = $config[$from]; unset($config[$from]);}
    }
    /* Write and return config file */
    $repl = [
      '/\s*array \(/' => ' [',
      '/,(\s*)\)/' => '${1}]',
      '/(\s+)\'am\' => \[/' => '$1/* AuthMe Configuration */$0',
      '/(\s+)\'sr\' => \[/' => '$1/* SkinsRestorer Configuration */$0',
      '/(\s+)\'cache_for_days\' => /' => '$1/* Cache Configuration */$0',
      '/(\s+)\'def_theme\' => /' => '$1/* Default theme for new users */$0'
    ];
    $confarr = preg_replace(array_keys($repl), $repl, var_export(array_replace_recursive($defaults, $config), true));
    $byteswritten = file_put_contents(__DIR__ . '/../config.nogit.php', "<?php return".$confarr.";?>");
    if (!$byteswritten) {printErrorAndDie('Did not create config file! ('.$byteswritten.'B written)');}
    return $config;
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(empty($_POST['release-version'])){ printErrorAndDie('Invalid Request! (Version Unspecified)'); }
    if(empty($_POST['thm-selection'])){ printErrorAndDie('Invalid Request! (Theme Unspecified)'); }
    $config['def_theme'] = $_POST['thm-selection'];

    /* Get Data from SkinsRestorer's config.yml */
    if(empty($_FILES['sr-config']['tmp_name'])){ printErrorAndDie('Invalid Request! (SkinsRestorer File)'); }
    $is_srconfig = preg_match('/MySQL:((?:\n\s+.*)*)/', file_get_contents($_FILES['sr-config']['tmp_name']), $re);
    if(!$is_srconfig){ printErrorAndDie('This file isn\'t SkinsRestorer\'s config!'); }
    preg_match_all('/\n\s*([^#\/:]+):[^\w.:]*([\w.: ]*[\w.:])/', $re[0], $re); 
    $kitms = ['enabled', 'host', 'port', 'database', 'skintable', 'playertable', 'username', 'password'];
    foreach ($re[1] as $k => $v) {$v = strtolower($v); if (in_array($v, $kitms)) {$config['sr'][$v]=$re[2][$k];};}
    if($config['sr']['enabled'] == false){ printErrorAndDie('Please make sure SkinsRestorerDB system is enabled!:'.$config['sr'][0]); }
    if($config['sr']['password'] == "''"){ $config['sr']['password'] = ''; }
    unset($config['sr']['enabled']);

    /* Get Data from AuthMe's config.yml */
    if(!empty($_POST['am-activation'])){
      $config['am']['enabled'] = true;
      if(empty($_FILES['am-config']['tmp_name'])){ printErrorAndDie('Invalid Request! (AuthMe File)'); }
      $is_srconfig = preg_match('/DataSource:((?:\n\s+.*)*)/', file_get_contents($_FILES['am-config']['tmp_name']), $re);
      if(!$is_srconfig){ printErrorAndDie('This file isn\'t AuthMe\'s config!'); }
      preg_match_all('/\n\s*(?:mySQL)?([^#\/:]+):[^\w.:]*([\w.: ]*[\w.:])/', $re[0], $re);
      $kitms = ['backend', 'enabled', 'host', 'port', 'database', 'username', 'password'];
      foreach ($re[1] as $k => $v) {$v = strtolower($v); if (in_array($v, $kitms)) {$config['am'][$v]=$re[2][$k];};}
      if($config['am']['backend'] !== 'MYSQL'){ printErrorAndDie('Please make sure AuthMeDB system is \'MYSQL\'!'); }
    } 
    unset($config['am']['backend']);

    /* Get non-default value for Authsecurity */
    if(empty($_POST['as-activation'])){$config['am']['authsec']['enabled'] = false;}

    /* Set default properties, write file */
    confupdater($config, $_POST['release-version']);
    printDataAndDie();
  }
?>
