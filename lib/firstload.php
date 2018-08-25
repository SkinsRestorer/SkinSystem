<?php

/*

	This file must always be loaded the first!

*/


//Check if settings.nogit.php exists
if (!file_exists(__DIR__.'/config.nogit.php') && $_SERVER['PHP_SELF'] != '/install/index.php') {
	Header('Location: /install/index.php');
	exit();
}
