<?php
  function printDataAndDie($data = []){
    if(!isset($data['success'])){ $data['success'] = empty($data['error']); }
    die(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
  }

  function printErrorAndDie($error){ printDataAndDie(['error' => $error]); }

  /* Check a directory. Can it create a config file?*/
  if(!is_writable('..')){
    printErrorAndDie('Try to \'chown www-data:www-data folderPath\' and \'chmod 775 folderPath\' (folderPath is the place that index.php live)');
  }

  if(empty($_FILES['sr-config']['tmp_name'])){ printErrorAndDie('Invalid Request'); }
  $raw_srconfig = file_get_contents($_FILES['sr-config']['tmp_name']);
  
  $sr_mySQLNode = substr($raw_srconfig, strpos($raw_srconfig, 'MySQL'), strpos($raw_srconfig, 'Updater') - strpos($raw_srconfig, 'MySQL'));
  if(empty($sr_mySQLNode)){ printErrorAndDie('This file isn\'t SkinsRestorer\'s config!'); }

  /* Get Data from SkinsRestorer's config.yml */
  $sr_sqlDataString = substr($sr_mySQLNode, strpos($sr_mySQLNode, 'Enabled'));
  $sr_enabled = @preg_replace('/\s+/', '', explode(':', substr($sr_sqlDataString, 0, strpos($sr_sqlDataString, 'Host')))[1]);
  if($sr_enabled == false){ printErrorAndDie('Please make sure SkinsRestorerDB system is enabled!'); }
  $sr_host = preg_replace('/\s+/', '', explode(':', substr($sr_sqlDataString, strpos($sr_sqlDataString, 'Host'), strpos($sr_sqlDataString, 'Port') - strpos($sr_sqlDataString, 'Host')))[1]);
  $sr_port = preg_replace('/\s+/', '', explode(':', substr($sr_sqlDataString, strpos($sr_sqlDataString, 'Port'), strpos($sr_sqlDataString, 'Database') - strpos($sr_sqlDataString, 'Port')))[1]);
  $sr_database = preg_replace('/\s+/', '', explode(':', substr($sr_sqlDataString, strpos($sr_sqlDataString, 'Database'), strpos($sr_sqlDataString, 'SkinTable') - strpos($sr_sqlDataString, 'Database')))[1]);
  $sr_skintable = preg_replace('/\s+/', '', explode(':', substr($sr_sqlDataString, strpos($sr_sqlDataString, 'SkinTable'), strpos($sr_sqlDataString, 'PlayerTable') - strpos($sr_sqlDataString, 'SkinTable')))[1]);
  $sr_playertable = preg_replace('/\s+/', '', explode(':', substr($sr_sqlDataString, strpos($sr_sqlDataString, 'PlayerTable'), strpos($sr_sqlDataString, 'Username') - strpos($sr_sqlDataString, 'PlayerTable')))[1]);
  $sr_username = preg_replace('/\s+/', '', explode(':', substr($sr_sqlDataString, strpos($sr_sqlDataString, 'Username'), strpos($sr_sqlDataString, 'Password') - strpos($sr_sqlDataString, 'Username')))[1]);
  $sr_password = preg_replace('/\s+/', '', explode(':', substr($sr_sqlDataString, strpos($sr_sqlDataString, 'Password')))[1]);
  if($sr_password == "''"){ $sr_password = ''; }

  if(!empty($_POST['am-activation'])){
    $am_enabled = 'true';
    if(empty($_FILES['am-config']['tmp_name'])){ printErrorAndDie('Invalid Request!'); }
    $raw_amconfig = file_get_contents($_FILES['am-config']['tmp_name']);

    $am_sqlDataString = substr($raw_amconfig, strpos($raw_amconfig, 'backend'), strpos($raw_amconfig, '# Table of the database') - strpos($raw_amconfig, 'backend'));
    if(empty($am_sqlDataString)){ printErrorAndDie('This file isn\'t Authme\'s config!'); }

    /* Get Data from Authme's config.yml */
    $am_backend = preg_replace('/\s+/', '', explode(':', substr($am_sqlDataString, strpos($am_sqlDataString, 'Backend'), strpos($am_sqlDataString, '# Enable the database')))[1]);
    if($am_backend == 'MYSQL'){ printErrorAndDie('Please make sure AuthmeDB system is \'MYSQL\'!'); }
    $am_host = preg_replace('/\s+/', '', explode(':', substr($am_sqlDataString, strpos($am_sqlDataString, 'mySQLHost'), strpos($am_sqlDataString, '# Database port') - strpos($am_sqlDataString, 'mySQLHost')))[1]);
    $am_port = preg_replace('/\s+/', '', explode(':', substr($am_sqlDataString, strpos($am_sqlDataString, 'mySQLPort'), strpos($am_sqlDataString, '# Connect to MySQL') - strpos($am_sqlDataString, 'mySQLPort')))[1]);
    $am_username = preg_replace('/\s+/', '', explode(':', substr($am_sqlDataString, strpos($am_sqlDataString, 'mySQLUsername'), strpos($am_sqlDataString, '# Password to connect') - strpos($am_sqlDataString, 'mySQLUsername')))[1]);
    $am_password = preg_replace('/\s+/', '', explode(':', substr($am_sqlDataString, strpos($am_sqlDataString, 'mySQLPassword'), strpos($am_sqlDataString, '# Database Name') - strpos($am_sqlDataString, 'mySQLPassword')))[1]);
    $am_database = preg_replace('/\s+/', '', explode(':', substr($am_sqlDataString, strpos($am_sqlDataString, 'mySQLDatabase'), strpos($am_sqlDataString, '# Database Name')))[1]);
  } else {
    $am_enabled = 'false';
  }
  if($am_enabled == 'false'){ $am_host = "''"; $am_port = "''"; $am_username = "''"; $am_password = "''"; $am_database = "''"; }

  /* Create Config file */
  $configFilePath = '../config.nogit.php';
  if(file_exists($configFilePath)){ printErrorAndDie('Config file already created!'); }
  $configFile = fopen($configFilePath, 'w') or printErrorAndDie('Cannot create config file!');

  fwrite($configFile, '<?php' . "\n");
  fwrite($configFile, '    return [' . "\n");
  fwrite($configFile, '        \'version\' => 1.6,' . "\n");
  fwrite($configFile, "        /* SkinsRestorer Configuration */" . "\n");
  fwrite($configFile, "        'sr' => [" . "\n");
  fwrite($configFile, "            'host' => '" . $sr_host . "'," . "\n");
  fwrite($configFile, "            'port' => '" . $sr_port . "'," . "\n");
  fwrite($configFile, "            'database' => '" . $sr_database . "'," . "\n");
  fwrite($configFile, "            'skintable' => '" . $sr_skintable . "'," . "\n");
  fwrite($configFile, "            'playretable' => '" . $sr_playertable . "'," . "\n");
  fwrite($configFile, "            'username' => '" . $sr_username . "'," . "\n");
  fwrite($configFile, "            'password' => '" . $sr_password . "'" . "\n");
  fwrite($configFile, '        ],' . "\n");
  fwrite($configFile, "        /* Authme Configuration */" . "\n");
  fwrite($configFile, "        'authme' => [" . "\n");
  fwrite($configFile, "            'enabled' => " . $am_enabled . "," . "\n");
  fwrite($configFile, "            'host' => " . $am_host . "," . "\n");
  fwrite($configFile, "            'port' => " . $am_port . "," . "\n");
  fwrite($configFile, "            'database' => " . $am_database . "," . "\n");
  fwrite($configFile, "            'username' => " . $am_username . "," . "\n");
  fwrite($configFile, "            'password' => " . $am_password . "" . "\n");
  fwrite($configFile, '        ]' . "\n");
  fwrite($configFile, '    ];');
  fclose($configFile);

  printDataAndDie();
?>
