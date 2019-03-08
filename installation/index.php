<?php
  if(file_exists('../config.nogit.php')){ die(header('Location: ../')); }
?>
<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SkinSystem - Installation</title>

    <!-- Libraries -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="../resources/css/styles.css">
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
                  <h5 class="mb-0"><i class="fas fa-wrench"></i> SkinSystem Installation <small style="font-size: 60%;">v.1.6</small></h5>
                </div>
              </div>
            <div class="card-body">
              <form id="installation-form">
                <div class="row">
                  <div class="col-lg-12 mb-lg-0">
                    <div id="alert" class="alert alert-danger" style="display: none;"><i class="fas fa-exclamation-circle"></i> <span>Error!</span></div>
                  </div>
                  <div class="col-lg-5 pr-lg-1 mb-lg-0 mb-3">
                    <div class="card border-0 shadow">
                      <h6 class="card-header bg-info text-white"><i class="fas fa-check"></i> Choices</h6>
                      <div class="card-body">
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input id="authme-activation" class="custom-control-input" type="checkbox" name="am-activation">
                            <label class="custom-control-label" for="authme-activation">Use with <strong>Authme</strong></label>
                            <small class="form-text text-muted">Do you want to allow users to only login and manage accounts that they have access to? <strong>This option is highly recomended!</strong></small>
                          </div>
                        </div>
                        <div id="authenticationsecurity-activation-form" class="form-group" style="display: none;">
                          <div class="custom-control custom-checkbox">
                            <input id="authenticationsecurity-activation" class="custom-control-input" type="checkbox" name="as-activation">
                            <label class="custom-control-label" for="authenticationsecurity-activation"><strong>Authentication</strong> Security</label>
                            <small class="form-text text-muted">Do you want to allow users can mistake their login for 3 times per day? <strong>This option is highly recomended!</strong></small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-7">
                    <div class="card border-0 shadow">
                      <h6 class="card-header bg-info text-white"><i class="fas fa-file-upload"></i> Upload</h6>
                      <div class="card-body">
                        <div class="form-group">
                          <label>Please select <strong>SkinsRestorer</strong> config.yml</label>
                          <div class="custom-file">
                            <input id="sr-config-input" class="custom-file-input" type="file" name="sr-config">
                            <label class="custom-file-label text-truncate">Choose a file...</label>
                          </div>
                        </div>
                        <div id="am-config-form" class="form-group" style="display: none;">
                          <label>Please select <strong>Authme</strong> config.yml</label>
                          <div class="custom-file">
                            <input id="am-config-input" class="custom-file-input" type="file" name="am-config">
                            <label class="custom-file-label text-truncate">Choose a file...</label>
                          </div>
                        </div>
                        <button class="btn btn-success w-100" type="submit"><i class="fas fa-cog"></i> Finish installation!</button>
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
