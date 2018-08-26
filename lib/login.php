<?php
/*
	Skin-System
	https://github.com/riflowth/SkinSystem
*/
 	/* Import Lib */
	require_once __DIR__ . '/lib.php';
	
	$ip = getIP();

	/* Check fail count and unban date */
	$query = skinsystemDBQuery("SELECT fail, unban_date FROM logincaching WHERE ipaddress = ?", [$ip]);
	$failData = $query->fetch(PDO::FETCH_ASSOC);
	if ($failData['fail'] >= 4){
		$today = sql_datetime();
		
		if($failData['unban_date'] > $today){
			printErrorAndDie([
				'block' => 'You have been blocked by the server because you inputted the wrong password for 4 times.'
			]);
		}
		
		skinsystemDBQuery("DELETE FROM logincaching WHERE ipaddress = ?", [$ip]);
	} 

 	/* Username or Password is empty */
	if (empty($_POST["username"])) {
		printErrorAndDie([
			'username' => 'Username is required.'
		]);
	}

	if (empty($_POST["password"])) {
		printErrorAndDie([
			'password' => 'Password is required.'
		]);
	}

	$username = strtolower($_POST["username"]);
	$password = $_POST["password"];

 	/* Fetch user */
	$authme = authmeDBQuery("SELECT username, password FROM ". $config['authme']['table'] ." WHERE username = ?", [$username]);
	$result = $authme->fetch(PDO::FETCH_ASSOC);

	/* Check Password */
	$hashParts = explode("$", $result["password"]);

	if (count($hashParts) === 4 && hash("sha256", hash("sha256", $password) . $hashParts[2]) === $hashParts[3]) {
		session_start();
		$_SESSION["username"] = $username;

		printDataAndDie();
	}

	/* Create logincaching table if not exist */
	skinsystemDBQuery("CREATE TABLE IF NOT EXISTS logincaching (
	ipaddress varchar(15) NOT NULL,
	fail tinyint(4) NOT NULL,
	unban_date datetime,
	PRIMARY KEY (ipaddress)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

	/* Check fail count */
	$query = skinsystemDBQuery("SELECT fail FROM logincaching WHERE ipaddress = ?", [$ip]);
	$failData = $query->fetch(PDO::FETCH_ASSOC);
	/* If not have any fail count, Insert fail count with 1 */
	if (!$failData) {
		skinsystemDBQuery('INSERT INTO logincaching (ipaddress, fail) VALUES (?, 1)', [$ip]);
	} else {
		$nextFailCount = $failData['fail'] + 1;
		$time = 0;

		if ($nextFailCount >= 4) {
			$time = strtotime('+1 hour');
		}

		/* If have any fail count, Increase fail count ++ */
		skinsystemDBQuery('UPDATE logincaching SET fail = ?, unban_date = ?', [
			$nextFailCount,
			sql_datetime($time)
		]);
	}

	printErrorAndDie([
		'password' => 'Username or password is incorrect.'
	]);
?>
