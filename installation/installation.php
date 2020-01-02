<?php
  if(file_exists(__DIR__ . '/../config.nogit.php')){ die(header('Location: ../')); }
  require_once '../resources/server/i18n.class.php';
  $i18n = new i18n();
  $i18n->setCachePath('../resources/lang/cache');
  $i18n->setFilePath('../resources/lang/{LANGUAGE}.ini'); // language file path
  $i18n->setFallbackLang('en');
  $i18n->setMergeFallback(true); // make keys available from the fallback language
  $i18n->init();
  function prntDataAndDie($data = []){
    if(!isset($data['success'])){ $data['success'] = empty($data['error']); }
    die(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
  }
  function prntErrorAndDie($error){ prntDataAndDie(['error' => $error]); }
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
      'data_warn' => 'no',
      'am' => [
        'enabled' => false,
        'host' => '',
        'port' => '',
        'database' => '',
        'username' => '',
        'password' => '',
        'hash' => [
          'method' => 'sha256'
        ],
        'authsec' => [
          'enabled' => true,
          'failed_attempts' => 3,
          'threshold_hours' => 24
        ]
      ],
      'sr' => [
        'host' => '',
        'port' => '',
        'database' => '',
        'skintable' => '',
        'playertable' => '',
        'username' => '',
        'password' => ''
      ],
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
      '/(\s+)\'am\' => \[/' => '$1/* '.L::config_am.' */$0',
      '/(\s+)\'sr\' => \[/' => '$1/* '.L::config_sr.' */$0',
      '/(\s+)\'cache_for_days\' => /' => '$1/* '.L::config_cache_for_days.' */$0',
      '/(\s+)\'def_theme\' => /' => '$1/* '.L::config_def_theme.' */$0',
      '/(\s+)\'data_warn\' => /' => '$1/* '.L::config_data_warn.' */$0'
    ];
    $confarr = preg_replace(array_keys($repl), $repl, var_export(array_replace_recursive($defaults, $config), true));
    $byteswritten = file_put_contents(__DIR__ . '/../config.nogit.php', "<?php return".$confarr.";?>");
    if (!$byteswritten) {prntErrorAndDie(L::instl_noconf);}
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(empty($_POST['release-version'])){ prntErrorAndDie(str_replace("%rsn%", L::instl_invreq_verusp, L::instl_invreq)); }
    if(empty($_POST['thm-selection'])){ prntErrorAndDie(str_replace("%rsn%", L::instl_invreq_thmusp, L::instl_invreq)); }
    $config['def_theme'] = $_POST['thm-selection'];

    /* Get Data from SkinsRestorer's config.yml */
    if(empty($_FILES['sr-config']['tmp_name'])){ prntErrorAndDie(str_replace("%rsn%", L::instl_invreq_srfile, L::instl_invreq)); }
    $is_srconfig = preg_match('/MySQL:((\s+.*)*)/', file_get_contents($_FILES['sr-config']['tmp_name']), $re);
    if(!$is_srconfig){ prntErrorAndDie(L::instl_srivfl); }
    preg_match_all('/\n\s*(\w+):\s*[\'"]?([\'"]{2}|[^\s\'"]+)/', $re[0], $re); 
    $kitms = ['enabled', 'host', 'port', 'database', 'skintable', 'playertable', 'username', 'password'];
    foreach ($re[1] as $k => $v) {$v = strtolower($v); if (in_array($v, $kitms)) {$config['sr'][$v]=$re[2][$k];};}
    if(!isset($config['sr']) || $config['sr']['enabled'] == false){ prntErrorAndDie(L::instl_srendb); }
    if(!isset($config['sr']) || $config['sr']['password'] == "''"){ $config['sr']['password'] = ''; }
    unset($config['sr']['enabled']);

    /* Get Data from AuthMe's config.yml */
    if(!empty($_POST['am-activation'])){
      $config['am']['enabled'] = true;
      if(empty($_FILES['am-config']['tmp_name'])){ prntErrorAndDie(str_replace("%rsn%", L::instl_invreq_amfile, L::instl_invreq)); }
      $raw_amconfig = file_get_contents($_FILES['am-config']['tmp_name']);
      $is_srconfig = preg_match('/DataSource:((?:\n\s+.*)*)/', $raw_amconfig, $re);
      if(!$is_srconfig){ prntErrorAndDie(L::instl_amivfl); }
      preg_match_all('/\n\s*(?:mySQL)?([^#\/:]+):\s*[\'"]?([\'"]{2}|[^\s\'"]+)/', $re[0], $re);
      $kitms = ['backend', 'enabled', 'host', 'port', 'database', 'username', 'password'];
      foreach ($re[1] as $k => $v) {$v = strtolower($v); if (in_array($v, $kitms)) {$config['am'][$v]=$re[2][$k];};}
      if($config['am']['backend'] !== 'MYSQL'){ prntErrorAndDie(L::instl_amendb); }
      if(preg_match('/\n\s*passwordHash:\s*[\'"]?([\'"]{2}|[^\s\'"]+)/', $raw_amconfig, $re)){
        $config['am']['hash']['method'] = strtolower($re[1]);
        if ($config['am']['hash']['method'] === 'pbkdf2') {
          if(preg_match('/\n\s*pbkdf2Rounds:\s*[\'"]?([\'"]{2}|[^\s\'"]+)/', $raw_amconfig, $re)){$config['am']['hash']['pbkdf2rounds'] = $re[1];}
        }
      }
    } 
    unset($config['am']['backend']);

    /* Get non-default value for Authsecurity */
    if(empty($_POST['as-activation'])){$config['am']['authsec']['enabled'] = false;}

    /* Set default properties, write file */
    confupdater($config, $_POST['release-version']);
    prntDataAndDie();
  }
?>
