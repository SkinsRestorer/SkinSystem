<?php
/*
	Skin-System
	https://github.com/riflowth/SkinSystem
*/
	/* Import config */
	require_once __DIR__ . '/config.nogit.php';
	global $config;

	/* Initialize PDO */
	$authmePDOinstance = new PDO('mysql:host=' . $config['mysql_authme_host'] . '; port=' . $config['mysql_authme_port'] . '; dbname=' . $config['mysql_authme_database'] . ';', $config['mysql_authme_username'], $config['mysql_authme_password']);
	$skinsystemPDOinstance = new PDO('mysql:host=' . $config['mysql_sr_host'] . '; port=' . $config['mysql_sr_port'] . '; dbname=' . $config['mysql_sr_database'] . ';', $config['mysql_sr_username'], $config['mysql_sr_password']);

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
