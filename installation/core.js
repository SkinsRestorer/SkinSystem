$(document).ready(function(){
  $("#am-activation").on("change", function(){
    if($("#am-activation")[0].checked == true){
      $("#as-activation-form").show();
      $("#am-config-form").show();
    } else {
      $("#as-activation-form").hide();
      $("#am-config-form").hide();
    }
  });

  $(".custom-file-input").on("change", function(){
    var fileName = $(this).val().split("\\").pop();
    if(fileName){
      $(this).next(".custom-file-label").html(fileName);
      console.log("File selected : " + fileName);
    } else {
      $(this).next(".custom-file-label").html("Choose a file...");
      console.log("No file selected!");
    }
  });

  $("#installation-form").on("submit", function(e){
    if($("#sr-config-input").val() == "" || ( $("#am-activation")[0].checked == true && $("#am-config-input").val() == "" )){
      $("#alert").show();
      $("#alert").find("span").html("Please select <strong>config.yml!</strong>");
      return false;
    }

    e.preventDefault();
    var data = new FormData($(this)[0]);
    $.ajax({
      type : "POST",
      url : "installation.php",
      cache : false,
      processData : false,
      contentType : false,
      data : data,
      dataType : "JSON",
      encode : true
    }).done(function(res){
      if(res.success){
        $("#alert").show();
        $("#alert").removeClass("alert-danger").addClass("alert-success");
        $("#alert").find("i").removeClass("fas fa-exclamation-circle").addClass("fas fa-check-circle");
        $("#alert").find("span").html("Installation success! Please wait...");
        setTimeout(function(){ location.reload(); }, 350);
      } else {
        $("#alert").show();
        $("#alert").find("span").html(res.error);
      }
    }).fail(function(){
      console.log("AJAX FAILED!!!");
      $("#alert").show();
      $("#alert").find("span").html("Cannot connect to the server!!!");
    });
  });
});
