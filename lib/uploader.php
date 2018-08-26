<?php
/*
	Skin-System
	https://github.com/riflowth/SkinSystem
*/

	require_once __DIR__ . '/lib.php';

	session_start();

	/* Authme */
	if (!empty($_SESSION["username"])) {
		$playername = $_SESSION["username"];
	} elseif ($config['authme']['enabled']) {
		printErrorAndDie('Please login first');
	} elseif (!empty($_POST['username'])) {
		$playername = $_POST['username'];
	}

	if (empty($playername)) {
		printErrorAndDie([
			'invalid' => 'Empty username'
		]);
	}

	/* If have some request to uploader.php without any POST */
	if (!isset($_POST['slim']) ||
		(empty($_FILES['file']['tmp_name']) && empty($_POST['url'])) ||
		empty($_POST['withURL'])
	) {
		printErrorAndDie('Invalid request');
	}

	/* Initial Feedback Variable */
	$data = [
		'username' => $playername,
		'slim' => false,
	];

	$postparam = [
		'visibility' => 0
	];

	/* Check Skintype POST*/
	if ($_POST["slim"] == "true") {
		$postparam['model'] = 'slim';
		$data['slim'] = true;
	}

	if ($_POST['withURL'] === 'true' && !empty($_POST['url'])) {
		$data["uploadtype"] = "url";

		$postparam['url'] = $_POST['url'];

		$url = "https://api.mineskin.org/generate/url";
	} else {
		$file = $_FILES['file']['tmp_name'];

		if (strncmp(mime_content_type($file), 'image/', 6) !== 0) {
			printErrorAndDie('Invalid image file');
		}

		$data["uploadtype"] = "file";
	
		$postparam['file'] =
			new CURLFile($_FILES['file']['tmp_name'], $_FILES['file']['type'], $_FILES['file']['name']);

		$url = "https://api.mineskin.org/generate/upload";
	}

	/* CURL System To Talk with RESTapi */
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postparam);
	$response = curl_exec($ch);
	
	if ($response === false) {
		printErrorAndDie([
			'curl' => curl_error($ch)
		]);
	}

	curl_close($ch);

	$json = json_decode($response, true);

	$transformedName = " " . $playername;

	if (empty($json['data']['texture']['value']) || empty($json['data']['texture']['signature'])) {
		printErrorAndDie([
			'invalid' => 'MineSkin API returned unusable data'
		]);
	}

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

	/* SQL Write/Read (Skins Table) */
	skinsystemDBQuery(
		"INSERT INTO {$config['sr']['tbl_skins']} (Nick, Value, Signature, timestamp) VALUES (?, ?, ?, ?) " .
		"ON DUPLICATE KEY UPDATE Nick=VALUES(Nick), Value=VALUES(Value), Signature=VALUES(Signature), " .
		"timestamp=VALUES(timestamp)",
		[$transformedName, $value, $signature, $timestamp]
	);

	/* SQL Write/Read (Players Table) */
	skinsystemDBQuery(
		"INSERT INTO {$config['sr']['tbl_players']} (Nick, Skin) VALUES (?, ?) " .
		"ON DUPLICATE KEY UPDATE Nick=VALUES(Nick), Skin=VALUES(Skin)",
		[$playername, $transformedName]
	);

	printDataAndDie($data);
?>
