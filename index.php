<?php
  if(!file_exists('config.nogit.php')){ session_start(); session_destroy(); die(header('Location: installation')); }

  require_once('resources/server/libraries.php');
  session_start();

  /* Set username session for non-authme system */
  if(empty($_SESSION['username']) && $config['authme']['enabled'] == false){ $_SESSION['username'] = 'SkinSystemUser'; }
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
    <?php echo '<link id="stylesheetSelector" rel="stylesheet" href="resources/css/'.
    ($_COOKIE['theme']!='' ? $_COOKIE['theme'] : ($config['deftheme']!='' ? $config['deftheme'] : 'light')).'.css">' ?>
    <!-- pick theme from cookie; if cookie invalid, pick default theme from config file; if config invalid, choose light theme -->
    <script type="text/javascript">
      function getCookie(cname) {
        var b = document.cookie.match('(^|[^;]+)\\s*' + cname + '\\s*=\\s*([^;]+)');
        return b ? b.pop() : '';
      } function setCookie(cname, cvalue) {
        var d = new Date(); d.setTime(d.getTime() + (365*24*60*60*1000)); // cookies will last a year
        document.cookie = cname + "=" + cvalue + ";expires="+ d.toUTCString() + ";path=/";
      } function toggleTheme() {
        if (getCookie("theme") == "dark") { setCookie("theme", "light"); }
        else { setCookie("theme", "dark"); }
        location.reload();
      } // skinViewer height shall match uploadSkin
      window.onresize = function () {
        document.getElementById('skinViewerContainer').style.height = document.getElementById('uploadSkinForm').clientHeight+'px';
      }
    </script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/94/three.min.js"></script>
    <script src="https://minerender.org/dist/skin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
  </head>
  <body class="bg-light">
    <!-- Main Container -->
    <section class="bg-light h-100">
      <div class="container h-100">
        <div class="row h-100">
          <div class="col-lg-<?php echo(!empty($_SESSION['username']) ? 8 : 6); ?> m-auto">
            <div class="card border-0 shadow">
              <div class="card-header bg-primary text-white">
                <div class="row mx-2 align-items-center">
                  <h5 class="mb-0">SkinSystem 
                    <small style="font-size: 60%;"><a id="versionDisplay" href="https://github.com/riflowth/SkinSystem/releases/tag/1.6">v.1.6</a> 
                      <?php if($config['version'] < getLatestVersion()){ echo ' <a href="https://github.com/riflowth/SkinSystem/releases/latest">(New version avaliable)</a>'; } ?>
                    </small>
                  </h5>
                  <h6 class="mb-0 ml-auto">
                    <?php if($config['authme']['enabled'] == true && !empty($_SESSION['username'])){ 
                      $SkullURL = '/skin/?vr=0&hr=0&headOnly=true&ratio=4&user='.$_SESSION['username'];
                      echo '<a class="skinDownload" href="/skin/?format=raw&dl=true&user='.$_SESSION['username'].
                      '"><img class="skinDownload" src="'.$SkullURL.'">    '.htmlspecialchars($_SESSION['username'], ENT_QUOTES).'</a>'; ?>
                      <a class="btn btn-sm btn-light ml-2 rounded-circle" href="resources/server/authenCore.php?logout"><i class="fas fa-sign-out-alt"></i></a>
                    <?php } ?>
                  </h6>
                  <a class="btn btn-sm btn-light ml-2 rounded-circle" onclick="toggleTheme();"><i class="fas fa-adjust"></i></a>
                </div>
              </div>
              <div class="card-body">
                <?php if(!empty($_SESSION['username'])){ ?>
                  <script src="resources/js/skinCore.js"></script>
                  <div class="row">
                    <!-- Uploader -->
                    <div class="col-lg-8 pr-lg-2 mb-lg-0 mb-3">
                      <div class="card border-0 shadow">
                        <h6 class="card-header bg-info text-white"><i class="fas fa-file-upload text-dark"></i> Upload</h6>
                        <div class="card-body">
                          <form id="uploadSkinForm">
                            <?php if($config['authme']['enabled'] == false){ ?>
                              <div class="form-group row">
                                <h5 class="col-lg-3"><span class="badge badge-success">Username</span></h5>
                                <div class="col-lg-9">
                                  <input id="input-username" class="form-control form-control-sm" name="username" type="text" required>
                                </div>
                              </div>
                            <?php } ?>
                            <div class="form-group" style="display: none;">
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
                                <input id="input-file" class="custom-file-input" name="file" type="file" accept="image/x-png,image/gif,image/jpeg" required>
                                <label class="custom-file-label text-truncate">Choose skin...</label>
                              </div>
                            </div>
                            <div id="form-input-url" class="form-group row" style="display: none;">
                              <div class="col-lg-12">
                                <input id="input-url" class="form-control form-control-sm" name="url" type="text" placeholder="Enter skin URL...">
                              </div>
                            </div>
                            <button class="btn btn-primary w-100"><strong>Upload!</strong></button>
                            <small class="form-text text-muted" id="uploadDisclaimer">Skins are sent to <a href="https://mineskin.org">mineskin.org</a>, <a href="https://mojang.com">mojang.com</a>, and <?php echo '<a href="/">'.$_SERVER['HTTP_HOST'].'</a>' ?></small>
                          </form>
                        </div>
                      </div>
                    </div>
                    <!-- Skin Viewer -->
                    <div class="col-lg-4">
                      <div class="card border-0 shadow">
                        <h6 class="card-header bg-info text-white"><i class="fas fa-eye text-dark"></i> Preview</h6>
                        <div class="card-body">
                          <div id="skinViewerContainer"></div>
                        </div>
                      </div>
                    </div>
                    <?php if(false){ ?>
                      <!-- Skin History -->
                      <div class="col-lg-12 mt-3">
                        <div class="card border-0 shadow">
                          <h6 class="card-header bg-success text-white"><i class="fas fa-history text-dark"></i> History <small>- You can use this skins by click on it</small></h6>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-2">
                                <img width="70px" src="https://api.mineskin.org/render/head?url=http://textures.minecraft.net/texture/92b6ec637d673537a2517a48d74d205737e0dee4497d6f64815b0e58f3071d09&skinName=">
                              </div>
                              <div class="col-2">
                                <img width="70px" src="https://api.mineskin.org/render/head?url=http://textures.minecraft.net/texture/9ee0a3258bfd46aecf080f0325210949025f5e9132f623f03c0c8650ed489a82&skinName=">
                              </div>
                              <div class="col-2">
                                <img width="70px" src="https://api.mineskin.org/render/head?url=http://textures.minecraft.net/texture/af38163eea863a9e9ce327ae117db3b1ce101124a155e1e70c368824d801ae1a&skinName=">
                              </div>
                              <div class="col-2">
                                <img width="70px" src="https://api.mineskin.org/render/head?url=http://textures.minecraft.net/texture/ee485352624c6953f90f025b1c5cc040c76684f38474cdf7719bdccc97af4078&skinName=">
                              </div>
                              <div class="col-2">
                                <img width="70px" src="https://api.mineskin.org/render/head?url=http://textures.minecraft.net/texture/eccc402ec034c8a6687781e0664797209c187965f8417c8b4725025dbf64b76f&skinName=">
                              </div>
                              <div class="col-2">
                                <img width="70px" src="https://api.mineskin.org/render/head?url=http://textures.minecraft.net/texture/92b6ec637d673537a2517a48d74d205737e0dee4497d6f64815b0e58f3071d09&skinName=">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
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
  <script type="text/javascript">window.onresize();</script>
</html>
