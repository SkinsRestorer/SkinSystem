<?php
/*
	Skin-System
	https://github.com/riflowth/SkinSystem


	>> I am a template! Configure me and rename me to config.nogit.php

*/

	$config =  [

		/* Authme Configuration */
		'authme' => false,
		'mysql_authme_host' => 'localhost',
		'mysql_authme_port' => '3306',
		'mysql_authme_username' => '',
		'mysql_authme_password' => '',
		'mysql_authme_db' => 'authme',
		'mysql_authme_table' => 'authme',

		/* SkinsRestorer Configuration */
		'mysql_sr_host' => 'localhost',
		'mysql_sr_port' => '3306',
		'mysql_sr_username' => '',
		'mysql_sr_password' => '',
		'mysql_sr_db' => 'skinsrestorer',
		'mysql_sr_tbl_players' => 'skins',
		'mysql_sr_tbl_skins' => 'skins',

		/* SkinSystem Configuration */
		'server_name' => 'Mc-Server',
		'skinhistory' => true,
		'is_public' => false,

		/* First-load install system */
		'is_installed' => false

	];
?>
