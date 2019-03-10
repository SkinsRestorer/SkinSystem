$(document).ready(function(){
  /* OnSubmit loginForm */
  $("#loginForm").on("submit", function(e){
    e.preventDefault();

    $.ajax({
      type : "POST",
      url : "resources/server/authenCore.php",
      data : $(this).serialize(),
      dataType : "JSON",
      encode : true,
    }).done(function(res){
      if(res.success){
        Swal.fire({
          type : "success",
          title : "Login Successfully!",
          text : "Enjoy with your skin",
          heightAuto : false
        });
        setTimeout(function(){ location.reload(); }, 1000);
      } else if(res.error.code == 404){
        Swal.fire({
          type : "error",
          title : "Something went wrong!",
          text : "Please check your username or password",
          heightAuto : false
        });
        console.log(res);
      } else {
        Swal.fire({
          type : "error",
          title : "Something went wrong!",
          text : "Please re-login or contact WebMaster",
          heightAuto : false
        });
        console.log(res.data);
      }
    }).fail(function(){
      console.log("[ERROR] AJAX FAILED!");
    });
  });
});
