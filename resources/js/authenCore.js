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
      Swal.fire(res);
      if (typeof(res.refresh) === 'number') {
        setTimeout(function(){ location.reload(); }, res.refresh);
      };
    }).fail(function(){
      console.log("[ERROR] AJAX FAILED!");
    });
  });
});