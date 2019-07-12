<?php
  if(file_exists(__DIR__ . '/../config.nogit.php')){ die(header('Location: ../')); }
  require_once '../resources/server/i18n.class.php';
  $i18n = new i18n();
  $i18n->setCachePath('../resources/lang/cache');
  $i18n->setFilePath('../resources/lang/{LANGUAGE}.ini'); // language file path
  $i18n->setFallbackLang('en');
  $i18n->setMergeFallback(true); // make keys available from the fallback language
  $i18n->init();
?>
<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo L::instl_title;?></title>

    <!-- Libraries -->
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <?php $themelist = [];
    foreach (glob(__DIR__ . '/../resources/themes/*.css') as $thm) { preg_match('/([^\/]*)\.css$/', $thm, $vl); $themelist[] = $vl[1];}
     echo '<link id="stylesheetSelector" rel="stylesheet" href="../resources/themes/'.$themelist[0].'.css">'; ?>
  </head>
  <body class="bg-light">
    <!-- Main Container -->
    <section class="bg-light h-100">
      <div class="container h-100">
        <div class="row h-100">
          <div class="col-lg-8 m-auto">
            <div class="card border-0 shadow">
              <div class="card-header bg-primary text-white">
                <div class="row mx-2 align-items-center">
                  <h5 class="mb-0"><i class="fas fa-wrench"></i> <?php echo L::instl_title;?> <small style="font-size: 60%;">v.<?php echo $_GET['v']; ?></small></h5>
                </div>
              </div>
            <div class="card-body">
              <form id="installation-form">
                <input id="release-version" name="release-version" type="text" value="<?php echo $_GET['v']; ?>" style="display: none;" />
                <div class="row">
                  <div class="col-lg-12 mb-lg-0">
                    <div id="alert" class="alert alert-danger" style="display: none;"><i class="fas fa-exclamation-circle"></i> <span><?php echo L::gnrl_error;?></span></div>
                  </div>
                  <div class="col-lg-5 pr-lg-1 mb-lg-0 mb-3">
                    <div class="card border-0 shadow">
                      <h6 class="card-header bg-info text-white"><i class="fas fa-check"></i> <?php echo L::instl_optns;?></h6>
                      <div class="card-body">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-sm" style="flex-grow:.1;padding-right:0px;">
                              <h5 class="mb-0 mr-3 custom-control-inline" style="padding-top:5px;">
                                <span class="badge badge-info"><?php echo L::instl_dfthm;?></span>
                              </h5>
                            </div>
                            <div class="col-sm" style="padding-left:0px;">
                              <select id="thm-selection" name="thm-selection" class="form-control" style="height: 35px;padding: 5px;" onchange="document.getElementById('stylesheetSelector').href='../resources/themes/'+this.value+'.css';">
                                <?php foreach ($themelist as $theme) {echo "<option>".$theme."</option>";} ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input id="am-activation" class="custom-control-input" type="checkbox" name="am-activation">
                            <label class="custom-control-label" for="am-activation"><?php echo L::instl_am1;?></label>
                            <small class="form-text text-muted"><?php echo L::instl_am2;?></small>
                          </div>
                        </div>
                        <div id="as-activation-form" class="form-group" style="display: none;">
                          <div class="custom-control custom-checkbox">
                            <input id="as-activation" class="custom-control-input" type="checkbox" name="as-activation">
                            <label class="custom-control-label" for="as-activation"><?php echo L::instl_al1;?></label>
                            <small class="form-text text-muted"><?php echo L::instl_al2;?></small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-7">
                    <div class="card border-0 shadow">
                      <h6 class="card-header bg-info text-white"><i class="fas fa-file-upload"></i> <?php echo L::instl_ldcnf;?></h6>
                      <div class="card-body">
                        <div class="form-group">
                          <label><?php echo L::instl_srcnf;?></label>
                          <div class="custom-file">
                            <input id="sr-config-input" class="custom-file-input" type="file" accept=".yml" name="sr-config">
                            <label class="custom-file-label text-truncate"><?php echo L::instl_cnfch;?></label>
                          </div>
                        </div>
                        <div id="am-config-form" class="form-group" style="display: none;">
                          <label><?php echo L::instl_amcnf;?></label>
                          <div class="custom-file">
                            <input id="am-config-input" class="custom-file-input" type="file" accept=".yml" name="am-config">
                            <label class="custom-file-label text-truncate"><?php echo L::instl_cnfch;?></label>
                          </div>
                        </div>
                        <button class="btn btn-success w-100" type="submit"><i class="fas fa-cog"></i> <?php echo L::instl_finish;?></button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Libraries -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/94/three.min.js"></script>
    <script src="core.js"></script>
  </body>
</html>
