$(document).ready(function(){
  /* Initialize Variables */
  var isSlim = false;
  var skinURL;

  /* OnSubmit uploadSkinForm */
  $("#uploadSkinForm").on("submit", function(e){
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
      if(res.success){
        Swal.fire({
          type : "success",
          title : "Upload Successful!",
          text : "Enjoy your skin",
          heightAuto : false
        });
        setTimeout(function(){ location.reload(); }, 350);
      } else {
        Swal.fire({
          type : "error",
          title : "Something went wrong!",
          text : res.error,
          heightAuto : false
        });
      }
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
  
  /* On skin load */
  if (document.querySelectorAll('a.skinDownload')[0].href) {
    skinURL = document.querySelectorAll('a.skinDownload')[0].href;
    skinChecker(function(){
      $("#skintype-alex").prop("checked", isSlim);
      $("#skintype-steve").prop("checked", !isSlim);
      render();
    });
  }

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

  /* RENDER FUNCTION */
  function render(){
    if(skinURL === undefined){ return; }

    if($('[id^=minerender-canvas-]')[0]){
      skinRender.clearScene();
    }

    skinRender.render({
      url : skinURL,
      slim : isSlim
    });
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
    skinChecker(function(){
      $("#skintype-alex").prop("checked", isSlim);
      $("#skintype-steve").prop("checked", !isSlim);
      render();
    });
  });

  /* If user changes skin URL */
  $("#input-url").on("input", function(){
    setTimeout(function(){ skinURL = $("#input-url").val(); }, 350);
    if(!$("#input-url").val()){ return; }
    skinChecker(function(){
      $("#skintype-alex").prop("checked", isSlim);
      $("#skintype-steve").prop("checked", !isSlim);
      render();
    });
  });

  /* If user changes skintype radios */
  $("[id^=skintype-]").on("change", function(){
    isSlim = !isSlim;
    render();
  });

  /* Change file name when user changes skin file */
  $(".custom-file-input").on("change", function(){
    var fileName = $(this).val().split("\\").pop();
    if(fileName){
      $(this).next(".custom-file-label").html(fileName);
      console.log("File selected : " + fileName);
    } else {
      $(this).next(".custom-file-label").html("Choose skin...");
      console.log("No file selected!");
    }
  });
});
