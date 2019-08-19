<?php
  require_once(__DIR__ . '/libraries.php');
  session_start();

  /* Initialize playername */
  if($config['am']['enabled'] == true && !empty($_SESSION['username'])){
    $playername = $_SESSION['username'];
  } else if($config['am']['enabled'] != true && !empty($_POST['username'])){
    $playername = $_POST['username'];
  }
  if(empty($playername)){
    printErrorAndDie(str_replace("%rsn%", L::skcr_error_plnm, L::skcr_error));
  }

  /* Check a request from users, Does it valid? --> If it valid do the statment below */
  if(!empty($_POST['isSlim']) && !empty($_POST['uploadtype']) && isset($_FILES['file']['tmp_name']) && isset($_POST['url'])){
    /* Initialize Data for sending to MineSkin API */
    $postparams = ['visibility' => 0];
    if($_POST['isSlim'] == 'true'){
      $postparams['model'] = 'slim';
    }
    /* Send with URL */
    if($_POST['uploadtype'] == 'url' && !empty($_POST['url'])){
      $postparams['url'] = $_POST['url'];
      $endpointURL = 'https://api.mineskin.org/generate/url';
    /* Send with File */
    } else {
      $file = $_FILES['file'];
      $validFileType = ['image/jpeg', 'image/png'];
      /* Check If the skin is a Minecraft's skin format */
      if(!in_array($file['type'], $validFileType)){ printErrorAndDie(L::skcr_skfmt); }
      list($skinWidth, $skinHeight) = getimagesize($file['tmp_name']);
      if(( $skinWidth != 64 && $skinHeight != 64 ) || ( $skinWidth != 64 && $skinHeight != 32 )){
        printErrorAndDie(L::skcr_invsk);
      }
      $postparams['file'] = new CURLFile($file['tmp_name'], $file['type'], $file['name']);
      $endpointURL = 'https://api.mineskin.org/generate/upload';
    }

    /* cURL to MineSkin API */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpointURL);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postparams);
    $response = curl_exec($ch);
    curl_close($ch);
    if($response == false){
      /* cURL ERROR */
      printErrorAndDie(str_replace("%rsn%", L::skcr_error_mscurl, L::skcr_error));
    }

    $json = json_decode($response, true);
    /* Prevent from duplicated casual skin of SkinsRestorer */
    $transformedName = ' ' . $playername;

    /* MineSkin API returned unusable data */
    if(empty($json['data']['texture']['value']) || empty($json['data']['texture']['signature'])){
      printErrorAndDie(str_replace("%rsn%", L::skcr_error_msinvl, L::skcr_error));
    }

    /* Assign data for putting to SkinsRestorer Storage */
    $value = $json['data']['texture']['value'];
    $signature = $json['data']['texture']['signature'];
    /*
      https://github.com/Th3Tr0LLeR/SkinsRestorer---Maro/blob/9358d5727cfc7a1dce4e2af9412679999be5b519/src/main/java/skinsrestorer/shared/storage/SkinStorage.java#L274
      From condition in SkinRestorer source code,
      ```
      if (timestamp + TimeUnit.MINUTES.toMillis(Config.SKIN_EXPIRES_AFTER) <= System.currentTimeMillis()) {
      ```
      Variable "timestamp", toMillis(...), currentTimeMillis() are long, except SKIN_EXPIRES_AFTER which is integer.
      This mean the left side of operator is less than or equal to Long.MAX_VALUE (2^63 - 1).
      Since we want to get maximum timestamp, we substitute SKIN_EXPIRES_AFTER with Integer.MAX_VALUE (2^31 - 1), and
      we get (2^31 - 1) * 60 * 1000 where 60 is used for convert to second and 1000 to convert to millisecond.
      To get maximum timestamp, we substract Long.MAX_VALUE with value above with get us:
      (2^63 - 1) - ((2^31 - 1) * 60 * 1000) = 9223243187835955807
    */
    $timestamp = "9223243187835955807";

    /* Storage Writing (Skins Table) */
    query('sr',
      "INSERT INTO {$config['sr']['skintable']} (Nick, Value, Signature, timestamp) VALUES (?, ?, ?, ?) " .
      "ON DUPLICATE KEY UPDATE Nick=VALUES(Nick), Value=VALUES(Value), Signature=VALUES(Signature), " .
      "timestamp=VALUES(timestamp)",
      [$transformedName, $value, $signature, $timestamp]
    );
    /* Storage Writing (Players Table) */
    query('sr',
      "INSERT INTO {$config['sr']['playertable']} (Nick, Skin) VALUES (?, ?) " .
      "ON DUPLICATE KEY UPDATE Nick=VALUES(Nick), Skin=VALUES(Skin)",
      [$playername, $transformedName]
    );
    printDataAndDie(['title'=>L::skcr_upld_title, 'text'=>L::skcr_upld_text, 'refresh'=>True]);
  }
  printErrorAndDie(str_replace("%rsn%", L::skcr_error_endprg, L::skcr_error));
?>
