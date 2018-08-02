<?php
/*
	Skin-System 
	https://github.com/riflowth/SkinSystem
*/	
	/* Import config */
	require_once("../config.php");
	global $config;
	
	/* Initialize PDO */
	$authmePDOinstance = new PDO("mysql:host=" . $config["mysql_host"] . "; dbname=" . $config["authme_mysql_database"] . ";", $config["mysql_username"], $config["mysql_password"]);
	$skinsystemPDOinstance = new PDO("mysql:host=" . $config["mysql_host"] . "; dbname=" . $config["skinsystem_mysql_database"] . ";", $config["mysql_username"], $config["mysql_password"]);

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
?>