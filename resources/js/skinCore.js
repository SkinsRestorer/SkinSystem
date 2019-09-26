$(document).ready(function(){
  /* Initialize Variables */
  var isSlim = false;
  var skinURL;

  /* OnSubmit uploadSkinForm */
  $("#uploadSkinForm").on("submit", function(e){
    // Swal.showLoading();
    e.preventDefault();
    var formData = new FormData($("#uploadSkinForm")[0]);
    $.ajax({
      type : "POST",
      url : "resources/server/skinCore.php",
      cache : false,
      contentType : false,
      processData : false,
      data : formData,
      dataType : "JSON",
      encode : true,
    }).done(function(res){
      Swal.fire(res);
      if (typeof(res.refresh) === 'number') {
        setTimeout(function(){ location.reload(); }, res.refresh);
      };
    }).fail(function(){
      console.log("[ERROR] AJAX FAILED!");
    });
  });

  /* If user changes uploadtype */
  $("[id^=uploadtype-]").on("change", function(){
    ['file', 'url'].forEach(function(nm) {
      if ($("#uploadtype-"+nm)[0].checked == true) {
        $("#form-input-"+nm).show();
        $("#input-"+nm).prop("required", true);
        $("#input-"+nm).trigger("change");
        $("#input-"+nm).trigger("input");
      }
      else {
        $("#form-input-"+nm).hide();
        $("#input-"+nm).prop("required", false);
      }
    });
  });

  /* Initialize MineSkin */
  var skinRender = new SkinRender({
    autoResize : true,
    controls : {
      enabled : false,
      zoom : false,
      rotate : false,
      pan : false
    },
    canvas : {
      height : $("#skinViewerContainer")[0].offsetHeight,
      width : $("#skinViewerContainer")[0].offsetWidth
    },
    camera : {
      x : 15,
      y : 25,
      z : 24,
      target: [0, 17, 0]
    }
  }, $("#skinViewerContainer")[0]);
  
  /* Add some animate to a model in SkinPreview */
  var startTime = Date.now();
  var t;
  $("#skinViewerContainer").on("skinRender", function(e){
    if(!e.detail.playerModel){ return; }
    e.detail.playerModel.rotation.y += 0.01;
    t = (Date.now() - startTime) / 1000;
    e.detail.playerModel.children[2].rotation.x = Math.sin(t * 5) / 2;
    e.detail.playerModel.children[3].rotation.x = -Math.sin(t * 5) / 2;
    e.detail.playerModel.children[4].rotation.x = Math.sin(t * 5) / 2;
    e.detail.playerModel.children[5].rotation.x = -Math.sin(t * 5) / 2;
  });

  /* Display user's current skin */
  if ($("#skinDownloadUrl").length) {renderUser($("#skinDownloadUrl").attr("name"));} // if logged in with authme
  $("#input-username").on("input", function(e) { // select by textbox
    renderUser($(e.target).val(), true);
  });
  function renderUser(username, delay=false) {
    skinURL = 'resources/server/skinRender.php?format=raw&user='+username;
    render(true, delay);
  }

  /* RENDER FUNCTION */
  var rendDelay;
  function render(checkskin=true, delay=false){
    if(skinURL === undefined){ return; }
    if (delay) {
      if (rendDelay) {window.clearTimeout(rendDelay);}
      rendDelay = window.setTimeout(render, 500);
    }
    else {
      if (checkskin) {
        skinChecker(function(){
          console.log('slimness: '+isSlim);
          $("#skintype-alex").prop("checked", isSlim);
          $("#skintype-steve").prop("checked", !isSlim);
          render(false, delay)
        });
      }
      else {
        if($('[id^=minerender-canvas-]')[0]){
          skinRender.clearScene();
        }
        console.log
        skinRender.render({
          url : skinURL,
          slim : isSlim
        });
      }
    }
  }

  /* Check what type of skins (Alex or Steve) */
  function skinChecker(callback){
    var image = new Image();
    image.crossOrigin = "Anonymous";
    image.src = skinURL;

    image.onload = function(){
      var detectCanvas = document.createElement("canvas");
      var detectCtx = detectCanvas.getContext("2d");
      detectCanvas.width = image.width;
      detectCanvas.height = image.height;
      detectCtx.drawImage(image, 0, 0);
      var px1 = detectCtx.getImageData(46, 52, 1, 12).data;
      var px2 = detectCtx.getImageData(54, 20, 1, 12).data;
      var allTransparent = true;
      for(var i = 3; i < 12 * 4; i += 4){
        if(px1[i] === 255){
          allTransparent = false;
          break;
        }
        if (px2[i] === 255) {
          allTransparent = false;
          break;
        }
      }
      isSlim = allTransparent;
      if(callback !== undefined){ callback(); }
    }
  }

  /* If user changes skin file */
  $("#input-file").on("change", function(event){
    if($("#input-file")[0].files.length === 0){ return; }
    skinURL = URL.createObjectURL(event.target.files[0]);
    render();
  });

  /* If user changes skin URL */
  $("#input-url").on("input", function(){
    setTimeout(function(){ skinURL = $("#input-url").val(); }, 350);
    if(!$("#input-url").val()){ return; }
    render(true, true);
  });

  /* If user changes skintype radios */
  $("[id^=skintype-]").on("change", function(){
    isSlim = !isSlim;
    render(false);
  });

  /* Change file name when user changes skin file */
  $(".custom-file-input").on("change", function(){
    var fileName = $(this).val().split("\\").pop();
    if(fileName){
      $(this).next(".custom-file-label").html(fileName);
      console.log("File selected : " + fileName);
    } else {
      $(this).next(".custom-file-label").html(l.uplty1_lbl);
      console.log("No file selected!");
    }
  });
});
