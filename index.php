<?php $release_version = '1.7';
  if(!file_exists('config.nogit.php')){ session_start(); session_destroy(); die(header('Location: installation/?v='.$release_version)); }
  require_once('resources/server/libraries.php');
  if($config['version'] != $release_version) {
    require_once('installation/installation.php');
    confupdater($config, $release_version);
    die(header("Refresh:0"));
  }
  session_start();

  /* Set username session for non-authme system */
  if(empty($_SESSION['username']) && $config['am']['enabled'] == false){ $_SESSION['username'] = 'SkinSystemUser'; }
?>
<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SkinSystem</title>
    <!-- Libraries -->
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <?php if (is_file('resources/themes/'.$_COOKIE['theme'].'.css')) { $theme = $_COOKIE['theme']; }
    else { $theme = $config['def_theme']; }
    echo '<link id="stylesheetSelector" rel="stylesheet" name="'.$theme.'" href="resources/themes/'.$theme.'.css">'; 
    // pick theme from cookie; if cookie invalid, pick default theme from config ?>
    <script type="text/javascript">
      function setCookie(cname, cvalue) {
        var d = new Date(); d.setTime(d.getTime() + (365*24*60*60*1000)); // cookies will last a year
        document.cookie = cname + "=" + cvalue + ";expires="+ d.toUTCString() + ";path=/";
      } 
      var theme = document.getElementById("stylesheetSelector").getAttribute("name");
      setCookie("theme", theme); // swap that stale cookie for a new one!
      function rotateTheme() { // move a metaphorical carousel by one item
        $.getJSON("resources/themes/",{}, function(lst){ 
          setCookie("theme", lst[((lst.indexOf(theme+".css")+1)%lst.length)].slice(0, -4));
          location.reload();
        });
      }
    </script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
  </head>
  <body class="bg-light">
    <!-- Main Container -->
    <section class="bg-light h-100">
      <div class="container h-100">
        <div class="row h-100">
          <div class="col-lg-6 m-auto">
            <div class="card border-0 shadow">
              <div class="card-header bg-primary text-white">
                <div class="row mx-2 align-items-center">
                  <h5 class="mb-0">SkinSystem 
                    <?php
                    echo '<small style="font-size: 60%;"><a id="versionDisplay" title="Release '.$config['version'].'" href="https://github.com/riflowth/SkinSystem/releases/tag/'.$config['version'].'">v.'.$config['version'].'</a>';
                      if($config['version'] < getLatestVersion()){ echo ' <a title="Latest Release" href="https://github.com/riflowth/SkinSystem/releases/latest">(New version avaliable)</a>'; } ?>
                    </small>
                  </h5>
                  <h6 class="mb-0 ml-auto">
                    <?php if($config['am']['enabled'] == true && !empty($_SESSION['username'])){ 
                      echo '<i class="fas fa-user"></i> '.htmlspecialchars($_SESSION['username'], ENT_QUOTES); ?></a>
                      <a class="btn btn-sm btn-light ml-2 rounded-circle" title="Log out" href="resources/server/authenCore.php?logout"><i class="fas fa-sign-out-alt"></i></a>
                    <?php } ?>
                  </h6>
                  <a class="btn btn-sm btn-light ml-2 rounded-circle" title="Switch theme" onclick="rotateTheme();"><i class="fas fa-adjust"></i></a>
                </div>
              </div>
              <div class="card-body">
                <?php if(!empty($_SESSION['username'])){ ?>
                  <script src="resources/js/skinCore.js"></script>
                    <div class="col-lg-16 pr-lg-2 mb-lg-0 mb-3">
                      <div class="card border-0 shadow">
                        <h6 class="card-header bg-info text-white"><i class="fas fa-file-upload text-dark"></i> Upload</h6>
                        <div class="card-body">
                          <form id="uploadSkinForm">
                            <?php if($config['am']['enabled'] == false){ ?>
                              <div class="form-group row">
                                <h5 class="col-lg-3"><span class="badge badge-success">Username</span></h5>
                                <div class="col-lg-9">
                                  <input id="input-username" class="form-control form-control-sm" name="username" type="text" required>
                                </div>
                              </div>
                            <?php } ?>
                            <div class="form-group">
                              <h5 class="mb-0 mr-3 custom-control-inline"><span class="badge badge-info">Skin Type</span></h5>
                              <div class="custom-control custom-radio custom-control-inline">
                                <input id="skintype-steve" class="custom-control-input" name="isSlim" value="false" type="radio">
                                <label class="custom-control-label" for="skintype-steve">Steve</label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                <input id="skintype-alex" class="custom-control-input" name="isSlim" value="true" type="radio">
                                <label class="custom-control-label" for="skintype-alex">Alex</label>
                              </div>
                            </div>
                            <div class="form-group mb-4">
                              <h5 class="mb-0 mr-3 custom-control-inline"><span class="badge badge-info">Upload Type</span></h5>
                              <div class="custom-control custom-radio custom-control-inline">
                                <input id="uploadtype-file" class="custom-control-input" name="uploadtype" value="file" type="radio" checked>
                                <label class="custom-control-label" for="uploadtype-file">File</label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                <input id="uploadtype-url" class="custom-control-input" name="uploadtype" value="url" type="radio">
                                <label class="custom-control-label" for="uploadtype-url">URL</label>
                              </div>
                            </div>
                            <div id="form-input-file" class="form-group">
                              <div class="custom-file">
                                <input id="input-file" class="custom-file-input" name="file" type="file" accept="image/*" required>
                                <label class="custom-file-label text-truncate">Choose skin...</label>
                              </div>
                            </div>
                            <div id="form-input-url" class="form-group row" style="display: none;">
                              <div class="col-lg-12">
                                <input id="input-url" class="form-control form-control-sm" name="url" type="text" placeholder="Enter skin URL...">
                              </div>
                            </div>
                            <button class="btn btn-primary w-100"><strong>Upload!</strong></button>
                            <!-- <small class="form-text text-muted" id="uploadDisclaimer">Skins are sent to <a href="https://mineskin.org">mineskin.org</a>, <a href="https://mojang.com">mojang.com</a>, and <a href="/"><?php echo $_SERVER['HTTP_HOST'] ?></a></small> -->
                          </form>
                        </div>
                    </div>
                    <!-- Uploader -->
                  </div>
                <?php } else { ?>
                  <script src="resources/js/authenCore.js"></script>
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="card border-0 shadow">
                        <h6 class="card-header bg-info text-white"><i class="fas fa-sign-in-alt"></i> Authenication</h6>
                        <div class="card-body">
                          <form id="loginForm">
                            <div class="input-group mb-3">
                              <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                              <input id="login-username" class="form-control" name="username" type="text" placeholder="Username" required>
                            </div>
                            <div class="input-group mb-3">
                              <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                              <input id="login-password" class="form-control" name="password" type="password" placeholder="Password" required>
                            </div>
                            <button class="btn btn-success w-100"><strong>Login!</strong></button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </body>
</html>
