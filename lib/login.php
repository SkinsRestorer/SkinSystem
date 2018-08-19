<?php
/*
	Skin-System
	https://github.com/riflowth/SkinSystem
*/
 	/* Import Lib */
	require_once __DIR__ . '/lib.php';
	
	/* Check fail count */
	$query = skinsystemDBQuery("SELECT fail FROM logincaching WHERE ipaddress = ?", [getIP()]);
	$failCount = $query->fetch(PDO::FETCH_ASSOC);
	if($failCount['fail'] == 4){
		$error = array();
		$data = array();
		
		$error['block'] = "You have been blocked by the server because you inputted the wrong password for 4 times.";
		$data["success"] = false;
		$data["error"] = $error;
		
		echo json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
		die();
	} 
	
	/* Initial Feedback Variable */
	$data = array();
	$error = array();
 	/* Username or Password is empty */
	if(empty($_POST["username"])){ $error["username"] = "Username is required."; }
	if(empty($_POST["password"])){ $error["password"] = "Password is required."; }
 	/* Username and Password are not empty */
	if(!empty($_POST["username"]) && !empty($_POST["password"])){
		$username = strtolower($_POST["username"]);
		$password = $_POST["password"];
 		$username_pass = false;
		$password_pass = false;
 		/* Check Username */
		$authme = authmeDBQuery("SELECT username FROM ". $config['authme']['table'] ." WHERE username = ?", [$username]);
		$result = $authme->fetch(PDO::FETCH_ASSOC);
 		if($result["username"] == $username){
			$username_pass = true;
		} else {
			$error["username"] = "Username is invalid.";
		}
 		/* Check Password */
		$authme = authmeDBQuery("SELECT password FROM " . $config['authme']['table'] . " WHERE username = ?", [$username]);
		$result = $authme->fetch(PDO::FETCH_ASSOC);
 		$hashParts = explode("$", $result["password"]);
		if(count($hashParts) === 4){
			if(hash("sha256", hash("sha256", $password) . $hashParts[2]) === $hashParts[3]){
				$password_pass = true;
			} else {
				/* Password is not correct */
				$error["password"] = "Password is invalid.";
				
				/* Create PasswordCaching table if not exist */
				skinsystemDBQuery("CREATE TABLE IF NOT EXISTS logincaching (
				ipaddress varchar(15) NOT NULL,
				fail tinyint(4) NOT NULL,
				PRIMARY KEY (ipaddress)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
				
				/* Check fail count */
				$query = skinsystemDBQuery("SELECT fail FROM logincaching WHERE ipaddress = ?", [getIP()]);
				$failCount = $query->fetch(PDO::FETCH_ASSOC);
				/* If not have any fail count, Insert fail count with 1 */
				if($failCount == false){
					skinsystemDBQuery("INSERT INTO logincaching (ipaddress, fail) VALUES (?, ?) ON DUPLICATE KEY UPDATE ipaddress=VALUES(ipaddress), fail=VALUES(fail)", [getIP(), 1]);
				} 
				/* If have any fail count, Increase fail count ++ */
				else if($failCount['fail'] == 1){
					skinsystemDBQuery("INSERT INTO logincaching (ipaddress, fail) VALUES (?, ?) ON DUPLICATE KEY UPDATE ipaddress=VALUES(ipaddress), fail=VALUES(fail)", [getIP(), 2]);
				}
				else if($failCount['fail'] == 2){
					skinsystemDBQuery("INSERT INTO logincaching (ipaddress, fail) VALUES (?, ?) ON DUPLICATE KEY UPDATE ipaddress=VALUES(ipaddress), fail=VALUES(fail)", [getIP(), 3]);
				}
				else if($failCount['fail'] == 3){
					skinsystemDBQuery("INSERT INTO logincaching (ipaddress, fail) VALUES (?, ?) ON DUPLICATE KEY UPDATE ipaddress=VALUES(ipaddress), fail=VALUES(fail)", [getIP(), 4]);
				}
			}
		}
 		/* Username and Password are correct */
		if($username_pass && $password_pass){
			session_start();
			$_SESSION["username"] = $username;
			$data["success"] = true;
		}
	}
 	/* Assign error to data array. When it has some error. */
	if($error){
		$data["success"] = false;
		$data["error"] = $error;
	}
 	echo json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
?>