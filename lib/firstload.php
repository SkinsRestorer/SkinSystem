<?php

/*

	This file must always be loaded the first!

*/


//Check if settings.nogit.php exists
if (!file_exists(__DIR__.'/config.nogit.php')){
	if (file_exists(__DIR__.'/config.yesgit.php')){
		die('config.nogit.php does not exist, but you do have config.yesgit.php. Please edit the config.yesgit.php in /lib/ folder and rename it to config.nogit.php');
	} else {
		die('config.nogit.php is missing, but it seems like you do not have config.yesgit.php either. Please, get the config.yesgit.php template from our GitHub repository, put it in /inc/util/ folder, edit it and rename it to config.nogit.php.');
	}
}
