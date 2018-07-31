<?php
/*
	Skin-System 
	RiFlowTH (Vectier Thailand) & Lion328 Development
*/

	/* Import Lib */
	require_once("lib.php");
	
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
		$authme = authmeDBQuery("SELECT username FROM authme WHERE username = ?", [$username]);
		$result = $authme->fetch(PDO::FETCH_ASSOC);
		
		if($result["username"] == $username){
			$username_pass = true;
		} else {
			$error["username"] = "Username is invalid.";
		}
		
		/* Check Password */
		$authme = authmeDBQuery("SELECT password FROM authme WHERE username = ?", [$username]);
		$result = $authme->fetch(PDO::FETCH_ASSOC);
		
		$hashParts = explode("$", $result["password"]);
		if(count($hashParts) === 4){
			if(hash("sha256", hash("sha256", $password) . $hashParts[2]) === $hashParts[3]){
				$password_pass = true;
			} else {
				$error["password"] = "Password is invalid.";
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
	
	echo json_encode($data);
?>