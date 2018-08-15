<!--
	Skin-System
	https://github.com/riflowth/SkinSystem
-->
<?php

	/* If session is dead, start new one */
	if (session_status() == PHP_SESSION_NONE){
	    session_start();
	}

	if (file_exists(__DIR__.'/../lib/config.nogit.php')){
		$config = require_once(__DIR__ . '/../lib/config.nogit.php');
		if ($config['sys']['is_installed'] === true) {
			Header('Location: /');
			exit();
		}
	}

?>
<!doctype html>
<html>
<head>
	<title>SkinSystem install</title>
	<meta charset="UTF-8">

	<link href="../css/styles.css" rel="stylesheet">
	<link rel="stylesheet" href="style.css">
	<link rel="shortcut icon" href="../src/favicon.ico" type="image/x-icon"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/94/three.min.js"></script>
	<script src="https://threejs.org/examples/js/controls/OrbitControls.js"></script>
	<script src="https://minerender.org/dist/skin.min.js"></script>
	<script src="https://static.aljaxus.eu/lib/jquery-form/jquery.form-3.51.0.js"></script>

	<!-- Toastr libs - https://codeseven.github.io/toastr/ -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" charset="utf-8"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" >
	<!-- Cookies lib -->
	<script src="https://static.aljaxus.eu/lib/js-cookie/js-cookie-v2.2.0.js" charset="utf-8"></script>
	<!-- Bootstrap toggle plugin -->
	<script src="https://static.aljaxus.eu/lib/bootstrap-toggle/2.2.2/bootstrap-toggle.min.js" charset="utf-8"></script>
	<link rel="stylesheet" href="https://static.aljaxus.eu/lib/bootstrap-toggle/2.2.2/bootstrap-toggle.min.css">
</head>
<body>
<style>
input[type=radio]{
	width: 1rem;
	height: 1rem;
	margin-right: .25rem;
}
</style>
<body>
<?php

if (isset($_GET['step']) && !empty($_GET['step'])) {																						// Check if $_GET['step'] is correctly defined
	if ( file_exists(__DIR__ . '/step-' . $_GET['step'] . '.php') ) {																		// Check if the file for the step exists
		?>

		<div class="row h-100 mx-0 align-items-center">
			<div class="col-lg-6 col-10 m-auto">
				<div class="card mb-5">
					<div class="card-header text-center">
						<h5 class="my-0">SkinSystem install - Step <?php echo $_GET['step']; ?></h5>
					</div>
					<div class="card-body">
						<div class="row">

							<?php
							include_once __DIR__ . '/step-' . $_GET['step'] . '.php';														// Include the step file
							?>

						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
	} else {
		echo('<script>window.location.href="./index.php?step=1&err=noFile";</script>');														// If the file does not exist, redirect to address for step one
	}
} else {
	echo('<script>window.location.href="./index.php?step=1&err=noStep";</script>');															// If $_GET['step'] is not correctly defined, redirect to address for step one
}

?>
</body>
</html>
