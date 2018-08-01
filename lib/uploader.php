<?php 
/*
	Skin-System
	RiFlowTH (Vectier Thailand) & Lion328 Development
*/
	require_once("../config.php");
	require_once("lib.php");
	
	/* Authme */
	session_start();
	if(!empty($_SESSION["username"])){
		$playername = $_SESSION["username"];
	} else {
		if ($config["authme"] === true) {
			die('Error: Please Login');
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
	echo "Your username : " . $playername . "<br>";
	echo $skinType;

	/* CURL Initialize */	
	$ch = curl_init();
	
	/* Parameters To Send */
	$check = getimagesize($_FILES['file']["tmp_name"]); // Check input is an image.
	if($check !== false){
		echo "<br>Upload with File<br>";
		
		$cfile = new CURLFile($_FILES['file']["tmp_name"], $_FILES['file']["type"], $_FILES['file']["name"]);
		$data = [
			"file" => $cfile,
			"visibility" => 1,
			"model" => $skinType,
		];
		
		$url = "https://api.mineskin.org/generate/upload";
	}
	else if($check === false){
		echo "<br>Upload with URL<br>";
		
		$url = $_POST["url"];
		$data = [
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
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$response = curl_exec($ch);
		
	if($response == true){	
		curl_close($ch);	
		$json = json_decode($response, true);
								
		$encryptname = " " . $playername;
		
		/* MineSkinAPI Reader */
		$value = $json['data']['texture']['value'];
		$signature = $json['data']['texture']['signature'];
		$timestamp = "9223243187835955807"; // 9223243187835955807 --> 2^63 - 1 - (2^31 -1) * 60 * 1000
		
		echo "<br>";
		echo "value: " . $value;
		echo "<br>";
		echo "signature: " . $signature;
				
		/* SQL Write/Read (Skins Table) */
		$db = skinsystemDBQuery("INSERT INTO skins (Nick, Value, Signature, timestamp) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE Nick=VALUES(Nick), Value=VALUES(Value), Signature=VALUES(Signature), timestamp=VALUES(timestamp)", [$encryptname, $value, $signature, $timestamp]);
		
		/* SQL Write/Read (Players Table) */
		$db = skinsystemDBQuery("INSERT INTO players (Nick, Skin) VALUES (?, ?) ON DUPLICATE KEY UPDATE Nick=VALUES(Nick), Skin=VALUES(Skin)", [$playername, $encryptname]);
				
		echo "Done !";
	}
	else {
		echo "Error : " . curl_error($ch);
	}
?>
