<?php
/*
	Skin-System
	https://github.com/riflowth/SkinSystem
*/
	/* Import config */
	$config = require_once(__DIR__ . '/config.nogit.php');
	global $config;

	/* Initialize PDO */
	$authmePDOinstance = new PDO('mysql:host=' . $config['authme']['host'] . '; port=' . $config['authme']['port'] . '; dbname=' . $config['authme']['database'] . ';', $config['authme']['username'], $config['authme']['password']);
	$skinsystemPDOinstance = new PDO('mysql:host=' . $config['sr']['host'] . '; port=' . $config['sr']['port'] . '; dbname=' . $config['sr']['database'] . ';', $config['sr']['username'], $config['sr']['password']);

	/* When working with Authme */
	function authmeDBQuery($mysqlcommand, $key = []){
		global $authmePDOinstance;
		$result = $authmePDOinstance->prepare($mysqlcommand);
		$result->execute($key);
		return $result;
	}

	/* When working with SkinSystem */
	function skinsystemDBQuery($mysqlcommand, $key = []){
		global $skinsystemPDOinstance;
		$result = $skinsystemPDOinstance->prepare($mysqlcommand);
		$result->execute($key);
		return $result;
	}
	
	/* Get lastest version of The SkinSystem */
	function getLatestVersion(){
		global $response;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/repos/riflowth/SkinSystem/releases/latest');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'The SkinSystem');
		$response = curl_exec($ch);
		curl_close($ch);
		
		if($response == true){
			$json = json_decode($response, true);
			return $json['tag_name'];
		} else {
			return 'cURL ERROR : ' . curl_error($ch);
		}
	}
	
	/* Get IP address of visitor */
	function getIP(){
		if(isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
			$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
			$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		}
		
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if(filter_var($client, FILTER_VALIDATE_IP)){
			$ip = $client;
		}
		else if(filter_var($forward, FILTER_VALIDATE_IP)){
			$ip = $forward;
		}
		else{
			$ip = $remote;
		}

		return $ip;
	}
?>
