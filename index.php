<!--
	Skin-System
	https://github.com/riflowth/SkinSystem
-->
<?php
	/* Initialize Structure */
	require_once __DIR__ . '/lib/firstload.php';
	require_once __DIR__ . '/lib/lib.php';

	/* If session is dead, start new one */
	if(session_status() == PHP_SESSION_NONE){
	    session_start();
	}

	/* If system install is not finished, redirect to install page */
	if (!$config['sys']['is_installed']) {
		Header('Location: ./install/');
		exit();
	}
	
	/* Show version */
	if($config['sys']['new_version_notify'] == true){
		$version = 'Current ' . $config['sys']['version'] . ' | Latest version ' . getLatestVersion();
	} else {
		$version = 'Current ' . $config['sys']['version'];
	}
?>
<!doctype html>
<html>
<head>
	<title><?php echo $config['sys']['name']; ?> SkinSystem</title>
	<meta charset="UTF-8">

	<link href="css/styles.css" rel="stylesheet">
	<link rel="shortcut icon" href="./src/favicon.ico" type="image/x-icon"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Libraries -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/94/three.min.js"></script>
	<script src="https://threejs.org/examples/js/controls/OrbitControls.js"></script>
	<script src="https://minerender.org/dist/skin.min.js" crossorigin="anonymous"></script>
	<script src="https://static.aljaxus.eu/lib/jquery-form/jquery.form-3.51.0.js"></script>

	<!-- Toastr libs - https://codeseven.github.io/toastr/ -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" charset="utf-8"></script>
	<link rel="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" href="/css/master.css">
	<!-- Cookies lib -->
	<script src="https://static.aljaxus.eu/lib/js-cookie/js-cookie-v2.2.0.js" charset="utf-8"></script>
</head>
<body>
<style>
input[type=radio]{
	width: 1rem;
	height: 1rem;
	margin-right: .25rem;
}
</style>
<?php
	if($config['authme']['enabled'] === true){
		if(isset($_SESSION["username"])){
?>
			<script src="lib/loader.js"></script>
			<div class="row h-100 mx-0 align-items-center">
				<div class="col-lg-6 col-10 m-auto">
					<div class="card mb-5">
						<div class="card-header text-center">
							<h5 class="my-0">SkinSystem</h5>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col">
									<form id="form" method="POST" enctype="multipart/form-data">
										<div class="form-group">
											<label for="input-username">Username</label>
											<input type="text" id="input-username" name="username" class="form-control" value="<?php echo $_SESSION['username']; ?>" disabled>
										</div>
										<div class="form-group">
											<div style="margin-bottom: .5rem;">Skin type</div>
											<div class="form-check form-check-inline">
												<input type="radio" id="input-skintype-alex" name="slim" value="true" checked>
												<label class="form-check-label" for="input-skintype-alex">Alex</label>
											</div>
											<div class="form-check form-check-inline">
												<input type="radio" id="input-skintype-steve" name="slim" value="false">
												<label class="form-check-label" for="input-skintype-steve">Steve</label>
											</div>
										</div>
										<div class="form-group">
											<div style="margin-bottom: .5rem;">Upload type</div>
											<div class="form-check form-check-inline">
												<input type="radio" id="input-uploadtype-file" name="withURL" value="false" checked>
												<label class="form-check-label" for="input-uploadtype-file">File</label>
											</div>
											<div class="form-check form-check-inline">
												<input type="radio" id="input-uploadtype-url" name="withURL" value="true">
												<label class="form-check-label" for="input-uploadtype-url">URL</label>
											</div>
										</div>
										<div id="upload-file" class="form-group">
											<div style="margin-bottom: .5rem;">File</div>
											<input type="file" id="input-file" name="file" accept="image/x-png,image/gif,image/jpeg" required>
										</div>
										<div id="upload-url" class="form-group" style="display: none;">
											<div style="margin-bottom: .5rem;">URL</div>
											<input type="url" id="input-url" name="url" class="form-control">
										</div>
										<button type="submit" class="btn btn-primary mt-4 w-100">Upload</button>
										<a href="lib/logout.php" class="btn btn-danger mt-4">Logout</a>
									</form>
								</div>
								<div class="col">
									<div class="card-title text-center">Skin viewer</div>
									<div id="skinrender-container" class="skinpreview h-100 w-100" style="-webkit-filter: drop-shadow(2px 2px 2px #222); filter: drop-shadow(2px 2px 2px #222);"></div>
								</div>
							</div>
						</div>
						<div class="card-footer text-center text-muted"><?php echo $version; ?></div>
					</div>
				</div>
			</div>
		<?php } else { ?>
		<div class="row h-100 mx-0 align-items-center">
			<div class="col-lg-4 col-10 m-auto">
				<div class="card mb-5">
					<div class="card-header text-center">
						<h4 class="my-0"><?php echo $config['sys']['name']; ?></h4>
					</div>
					<div class="card-body">
						<p class="card-title text-center" style="font-size: 1.2rem;">SkinSystem</p>
						<!-- Login Form -->
						<form id="form-login" method="POST" enctype="multipart/form-data">
							<div class="form-group">
								<label for="username">Username</label>
								<input type="text" id="username" class="form-control" placeholder="username">
							</div>
							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" id="password" class="form-control" placeholder="password">
							</div>
							<button type="submit" class="btn btn-primary w-100">Login</button>
						</form>
					</div>
					<div class="card-footer text-center text-muted"><?php echo $version; ?></div>
				</div>
			</div>
		</div>
<?php } ?>
<?php } else { ?>
		<script src="lib/loader.js"></script>
		<div class="row h-100 mx-0 align-items-center">
			<div class="col-lg-6 col-10 m-auto">
				<div class="card mb-5">
					<div class="card-header text-center">
						<h5 class="my-0">SkinSystem</h5>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col">
								<form id="form" method="POST" enctype="multipart/form-data">
									<div class="form-group">
										<label for="input-username">Username</label>
										<input type="text" id="input-username" name="username" class="form-control disable" pattern="^[a-zA-Z0-9_]{3,16}$" required>
									</div>
									<div class="form-group">
										<div style="margin-bottom: .5rem;">Skin type</div>
										<div class="form-check form-check-inline">
											<input type="radio" id="input-skintype-alex" name="slim" value="true" checked>
											<label class="form-check-label" for="input-skintype-alex">Alex</label>
										</div>
										<div class="form-check form-check-inline">
											<input type="radio" id="input-skintype-steve" name="slim" value="false">
											<label class="form-check-label" for="input-skintype-steve">Steve</label>
										</div>
									</div>
									<div class="form-group">
										<div style="margin-bottom: .5rem;">Upload type</div>
										<div class="form-check form-check-inline">
											<input type="radio" id="input-uploadtype-file" name="withURL" value="false" checked>
											<label class="form-check-label" for="input-uploadtype-file">File</label>
										</div>
										<div class="form-check form-check-inline">
											<input type="radio" id="input-uploadtype-url" name="withURL" value="true">
											<label class="form-check-label" for="input-uploadtype-url">URL</label>
										</div>
									</div>
									<div id="upload-file" class="form-group">
										<div style="margin-bottom: .5rem;">File</div>
										<input type="file" id="input-file" name="file" accept="image/x-png,image/gif,image/jpeg" required>
									</div>
									<div id="upload-url" class="form-group" style="display: none;">
										<div style="margin-bottom: .5rem;">URL</div>
										<input type="url" id="input-url" name="url" class="form-control">
									</div>
									<button type="submit" class="btn btn-primary mt-4 w-100">Upload</button>
								</form>
							</div>
							<div class="col">
								<div class="card-title text-center">Skin viewer</div>
								<div id="skinrender-container" class="skinpreview h-100 w-100" style="-webkit-filter: drop-shadow(2px 2px 2px #222); filter: drop-shadow(2px 2px 2px #222);"></div>
							</div>
						</div>
					</div>
					<div class="card-footer text-center text-muted"><?php echo $version; ?></div>
				</div>
			</div>
		</div>
<?php } ?>
<script src="lib/login.js"></script>
</body>
</html>
