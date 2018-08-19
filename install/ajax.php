<?php
$statusData = array(
	'isset' => array(
		'get' => null,
		'post' => null,
		'session' => null
	),
	'debug' => array(
		'time' => time(),
		'get' => null,
		'post' => null,
		'session' => null
	),
	'messages' => array(),
	'data' => array(
		'sr_conn' => null,
		'sr_tbl_skins' => null,
		'sr_tbl_players' => null,

		'auth_conn' => null,
		'auth_table' => null,

		'sys_perms' => null
	),
	'is_success' => false
);

if (isset($_GET) && !empty($_GET)){
	$statusData['isset']['get'] = true;
	$statusData['debug']['get'] = $_GET;
}

if (isset($_SESSION) && !empty($_SESSION)){
	$statusData['isset']['session'] = true;
	$statusData['debug']['session'] = $_SESSION;
}

if (isset($_POST) && !empty($_POST)){
	$statusData['isset']['post'] = true;
	$statusData['debug']['post'] = $_POST;

	if (
		isset($_POST['sys_name']) && !empty($_POST['sys_name']) &&
		isset($_POST['sys_skinhistory']) && !empty($_POST['sys_skinhistory']) &&
		isset($_POST['sys_ispublic']) && !empty($_POST['sys_ispublic']) &&

		isset($_POST['auth_enabled']) && !empty($_POST['auth_enabled']) &&
		isset($_POST['auth_host']) && !empty($_POST['auth_host']) &&
		isset($_POST['auth_port']) && !empty($_POST['auth_port']) &&
		isset($_POST['auth_username']) && !empty($_POST['auth_username']) &&
		isset($_POST['auth_password']) &&
		isset($_POST['auth_database']) && !empty($_POST['auth_database']) &&
		isset($_POST['auth_table']) && !empty($_POST['auth_table']) &&

		isset($_POST['sr_host']) && !empty($_POST['sr_host']) &&
		isset($_POST['sr_port']) && !empty($_POST['sr_port']) &&
		isset($_POST['sr_username']) && !empty($_POST['sr_username']) &&
		isset($_POST['sr_password']) &&
		isset($_POST['sr_database']) && !empty($_POST['sr_database']) &&
		isset($_POST['sr_tbl_skins']) && !empty($_POST['sr_tbl_skins']) &&
		isset($_POST['sr_tbl_players']) && !empty($_POST['sr_tbl_players'])
	){
		{
			array_push(
				$statusData['messages'],
				array(
					'color' => 'green',
					'msg' => json_encode(array('[success]' => 'Test for all provided variables succeeded'), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
				)
			);
		}
		{
			try {
				$statusData['data']['sr_conn'] = true;

				$conn = new pdo( 'mysql:host='.$_POST['sr_host'].':'.$_POST['sr_port'].';dbname='.$_POST['sr_database'],
					$_POST['sr_username'],
					$_POST['sr_password'],
					array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
				);

				array_push(
					$statusData['messages'],
					array(
						'color' => 'green',
						'msg' => json_encode(array('[success]' => 'Testing authme MySql credentials succeeded'), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
					)
				);

				{
					if ($conn->query("SHOW TABLES LIKE '".$_POST['sr_tbl_skins']."';")->rowCount() == 1){
						$statusData['data']['sr_tbl_skins'] = true;
						array_push(
							$statusData['messages'],
							array(
								'color' => 'green',
								'msg' => json_encode(array('[success]' => 'Test for skinsrestorer MySql - '.$_POST['sr_tbl_skins'].' table succeeded'), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
							)
						);
					} else {
						$statusData['data']['sr_tbl_skins'] = false;
						array_push(
							$statusData['messages'],
							array(
								'color' => 'red',
								'msg' => json_encode(array('[error]' => 'Test for skinsrestorer MySql - '.$_POST['sr_tbl_skins'].' table failed!'), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
							)
						);
					}
				}

				{
					if ($conn->query("SHOW TABLES LIKE '".$_POST['sr_tbl_players']."';")->rowCount() == 1){
						$statusData['data']['sr_tbl_players'] = true;
						array_push(
							$statusData['messages'],
							array(
								'color' => 'green',
								'msg' => json_encode(array('[success]' => 'Test for skinsrestorer MySql - '.$_POST['sr_tbl_players'].' table succeeded'), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
							)
						);
					} else {
						$statusData['data']['sr_tbl_players'] = false;
						array_push(
						$statusData['messages'],
							array(
								'color' => 'red',
								'msg' => json_encode(array('[error]' => 'Test for skinsrestorer MySql - '.$_POST['sr_tbl_players'].' table failed!'), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
							)
						);
					}
				}

			} catch (PDOException $ex){

				$statusData['data']['sr_conn'] = false;
				array_push(
				$statusData['messages'],
					array(
						'color' => 'red',
						'msg' => json_encode(array('Testing skinsrestorer MySql:' => false, '[error]' => $ex), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
					)
				);
			}
		}
		{
			try {
				$statusData['data']['auth_conn'] = true;
				$conn = new pdo( 'mysql:host='.$_POST['auth_host'].':'.$_POST['auth_port'].';dbname='.$_POST['auth_database'],
					$_POST['auth_username'],
					$_POST['auth_password'],
					array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
				);

				array_push(
					$statusData['messages'],
					array(
						'color' => 'green',
						'msg' => json_encode(array('[success]' => 'Authme MySql test succeeded'), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
					)
				);

				{
					if ($conn->query("SHOW TABLES LIKE '".$_POST['auth_table']."';")->rowCount() == 1){
						$statusData['data']['auth_table'] = true;
						array_push(
							$statusData['messages'],
							array(
								'color' => 'green',
								'msg' => json_encode(array('[success]' => 'Test for Authme MySql - '.$_POST['auth_table'].' table succeeded'), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
							)
						);
					} else {
						$statusData['data']['auth_table'] = false;
						array_push(
							$statusData['messages'],
							array(
								'color' => 'red',
								'msg' => json_encode(array('[error]' => 'Test for Authme MySql - '.$_POST['auth_table'].' table failed!'), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
							)
						);
					}
				}

			} catch (PDOException $ex){
				$statusData['data']['auth_conn'] = false;
				array_push(
					$statusData['messages'],
					array(
						'color' => 'red',
						'msg' => json_encode(array('[error]' => 'Authme MySql test failed!', '[error]' => $ex), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
					)
				);
			}
		}
		{
			if ( is_writable(__DIR__ . '/../lib') ){
				$statusData['data']['sys_perms'] = true;
				array_push(
					$statusData['messages'],
					array(
						'color' => 'green',
						'msg' => json_encode(array('[success]' => 'The /lib directory has valid permissions'), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
					)
				);
			} else {
				$statusData['data']['sys_perms'] = false;
				array_push(
					$statusData['messages'],
					array(
						'color' => 'red',
						'msg' => json_encode(array('[error]' => 'The /lib directory has invalid permissions'), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
					)
				);
			}
		}
		{
			if ( !in_array(false, $statusData['data']) ){
				array_push(
					$statusData['messages'],
					array(
						'color' => 'green',
						'msg' => json_encode(array('[info]' => 'Checking if everything is alright', '[success]' => 'Continuing with installation...'), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
					)
				);
				{
					$temp1 = true;
					$configFilePath = __DIR__ . '/../lib/config.nogit.php';
					if (file_exists($configFilePath)){unlink($configFilePath);}
					$configFile = fopen($configFilePath, 'w') or $temp1 = false;

					if ($temp1){

						fwrite($configFile, '<?php'."\n");
						fwrite($configFile, '/*'."\n");
						fwrite($configFile, '	Skin-System'."\n");
						fwrite($configFile, '	https://github.com/riflowth/SkinSystem'."\n");
						fwrite($configFile, '*/'."\n");
						fwrite($configFile, ' '."\n");
						fwrite($configFile, '	return ['."\n");
						fwrite($configFile, "		'authme' => array("."\n");
						fwrite($configFile, "			/* Authme Configuration */"."\n");
						fwrite($configFile, "			'enabled' => ".$_POST['auth_enabled'].","."\n");
						fwrite($configFile, "			'host' => '".$_POST['auth_host']."',"."\n");
						fwrite($configFile, "			'port' => '".$_POST['auth_port']."',"."\n");
						fwrite($configFile, "			'username' => '".$_POST['auth_username']."',"."\n");
						fwrite($configFile, "			'password' => '".$_POST['auth_password']."',"."\n");
						fwrite($configFile, "			'database' => '".$_POST['auth_database']."',"."\n");
						fwrite($configFile, "			'table' => '".$_POST['auth_table']."'"."\n");
						fwrite($configFile, "		),"."\n");
						fwrite($configFile, "		'sr' => array("."\n");
						fwrite($configFile, "			/* SkinsRestorer Configuration */"."\n");
						fwrite($configFile, "			'host' => '".$_POST['sr_host']."',"."\n");
						fwrite($configFile, "			'port' => '".$_POST['sr_port']."',"."\n");
						fwrite($configFile, "			'username' => '".$_POST['sr_username']."',"."\n");
						fwrite($configFile, "			'password' => '".$_POST['sr_password']."',"."\n");
						fwrite($configFile, "			'database' => '".$_POST['sr_database']."',"."\n");
						fwrite($configFile, "			'tbl_skins' => '".$_POST['sr_tbl_skins']."',"."\n");
						fwrite($configFile, "			'tbl_players' => '".$_POST['sr_tbl_players']."'"."\n");
						fwrite($configFile, "		),"."\n");
						fwrite($configFile, "		'sys' => array("."\n");
						fwrite($configFile, "			/* SkinSystem Configuration */"."\n");
						fwrite($configFile, "			'name' => '".$_POST['sys_name']."',"."\n");
						fwrite($configFile, "			'skinhistory' => ".$_POST['sys_skinhistory'].","."\n");
						fwrite($configFile, "			'is_public' => ".$_POST['sys_ispublic'].","."\n");
						fwrite($configFile, "			'new_version_notify' => true,"."\n");
						fwrite($configFile, "			/* Do-not-touch part */"."\n");
						fwrite($configFile, "			'version' => 'Version 1.5',"."\n");
						fwrite($configFile, "			/* First-load install system */"."\n");
						fwrite($configFile, "			'is_installed' => true"."\n");
						fwrite($configFile, "		)"."\n");
						fwrite($configFile, "	];"."\n");
						fwrite($configFile, "?>"."\n");

						fclose($configFile);

						array_push(
							$statusData['messages'],
							array(
								'color' => 'green',
								'msg' => json_encode(array('[success]' => 'Successfully created the config file.'), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
							)
						);
						$statusData['is_success'] = true;
					} else {
						array_push(
							$statusData['messages'],
							array(
								'color' => 'red',
								'msg' => json_encode(array('[error]' => 'Unable to create config file. Something is very wrong, please report this issue on GitHub!'), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
							)
						);
					}
				}
			} else {
				array_push(
					$statusData['messages'],
					array(
						'color' => 'red',
						'msg' => json_encode(array('[error]' => 'Something is not okay.', '[info]' => 'Quitting installation proccess...'), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
					)
				);
			}
		}
	} else {
		array_push(
			$statusData['messages'],
			array(
				'color' => 'red',
				'msg' => json_encode(array('[error]' => 'Not all variables were correctly provided', '[info]' => 'Please recheck every input field and make sure it is corrctly filled in.'), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
			)
		);
	}


}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
Header('Content-Type: application/json');
echo json_encode($statusData, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
