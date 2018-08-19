<?php
/*
	Skin-System
	https://github.com/riflowth/SkinSystem
*/

	require_once __DIR__ . '/lib.php';

	/* Initial Feedback Variable */
	$data = array();
	$error = array();

	/* If have some request to uploader.php without any POST */
	if(!isset($_POST["slim"])) {
		$data["ERROR"] = "Nothing here! You can't do that !!!";
		echo json_encode($data);
		die();
	}

	/* Authme */
	session_start();
	if(!empty($_SESSION["username"])){
		$playername = $_SESSION["username"];
	} else {
		if($config["authme"] === true){
			die("Login first !");
		} else {
			$playername = $_POST["username"];
		}
	}

	/* Check Skintype POST*/
	if($_POST["slim"] == "true"){
		$skinType = "slim";
	}
	else if($_POST["slim"] == "false"){
		$skinType = "";
	}

	/* Feedback */
	$data["username"] = $playername;
	$data["slim"] = $skinType;

	/* CURL Initialize */
	$ch = curl_init();

	/* Parameters To Send */
	$check = getimagesize($_FILES['file']["tmp_name"]); // Check input is an image.
	if($check !== false){
		$data["uploadtype"] = "file";

		$cfile = new CURLFile($_FILES['file']["tmp_name"], $_FILES['file']["type"], $_FILES['file']["name"]);
		$postparam = [
			"file" => $cfile,
			"visibility" => 1,
			"model" => $skinType,
		];

		$url = "https://api.mineskin.org/generate/upload";
	}
	else if($check === false){
		$data["uploadtype"] = "url";

		$url = $_POST["url"];
		$postparam = [
			"url" => $url,
			"visibility" => 1,
			"model" => $skinType,
		];

		$url = "https://api.mineskin.org/generate/url";
	}

	/* CURL System To Talk with RESTapi */
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postparam);
	$response = curl_exec($ch);
	curl_close($ch);
	
	if($response == true){
		$json = json_decode($response, true);

		$encryptname = " " . $playername;

		/* MineSkinAPI Reader */
		$value = $json['data']['texture']['value'];
		$signature = $json['data']['texture']['signature'];
		
		$timestamp = "9223243187835955807";
		/*
			[ TimeStamp ]
			Max Long - Max Integer (minute) * 60 * 1000 (millisecond)
			2^63 - 1 - (2^31 -1) * 60 * 1000 = 9223243187835955807 	
			https://github.com/Th3Tr0LLeR/SkinsRestorer---Maro/blob/master/src/main/java/skinsrestorer/shared/storage/SkinStorage.java#L274
		*/

		/* If important variables aren't empty */
		if(!empty($playername) && !empty($value) && !empty($signature)) {
			/* SQL Write/Read (Skins Table) */
			$db = skinsystemDBQuery("INSERT INTO ? (Nick, Value, Signature, timestamp) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE Nick=VALUES(Nick), Value=VALUES(Value), Signature=VALUES(Signature), timestamp=VALUES(timestamp)", [$config['mysql_sr_tbl_skins'], $encryptname, $value, $signature, $timestamp]);

			/* SQL Write/Read (Players Table) */
			$db = skinsystemDBQuery("INSERT INTO ? (Nick, Skin) VALUES (?, ?) ON DUPLICATE KEY UPDATE Nick=VALUES(Nick), Skin=VALUES(Skin)", [$config['mysql_sr_tbl_players'], $playername, $encryptname]);

			$data["success"] = true;
		} else {
			$error["Invalid"] = "Invalid parameters !";
		}
	}
	else {
		$error["curl"] = "cURL ERROR : " . curl_error($ch);
	}

	/* Assign error to data array. When it has some error. */
	if($error){
		$data["success"] = false;
		$data["error"] = $error;
	}

	echo json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
?>
