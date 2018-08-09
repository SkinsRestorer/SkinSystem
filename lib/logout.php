<?php
/*
	Skin-System
	https://github.com/riflowth/SkinSystem
*/

if (session_status() == PHP_SESSION_NONE){
	session_start();
	session_destroy();
}
header("Location: /");
