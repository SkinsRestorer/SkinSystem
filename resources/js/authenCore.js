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
          title : "Login Successful!",
          text : "Enjoy your skins",
          footer : res.footer,
          heightAuto : false
        });
        setTimeout(function(){ location.reload(); }, 350);
      } else if(res.error.code == 401){
        Swal.fire({
          type : "warning",
          title : "Invalid username/password!",
          text : res.error.data,
          footer : res.footer,
          heightAuto : false
        });
        console.log(res);
      } else if(res.error.code == 429){
        Swal.fire({
          type : "error",
          title : "You're rate limited!",
          text : res.error.data,
          footer : res.footer,
          heightAuto : false
        });
        console.log(res);
      } else {
        Swal.fire({
          type : "error",
          title : "Something went wrong!",
          text : "Please try again or contact WebMaster",
          footer : res.footer,
          heightAuto : false
        });
        console.log(res.data);
      }
    }).fail(function(){
      console.log("[ERROR] AJAX FAILED!");
    });
  });
});
