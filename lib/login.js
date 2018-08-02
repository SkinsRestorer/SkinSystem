/*
	Skin-System 
	https://github.com/riflowth/SkinSystem
*/

$(document).ready(function(){
	/* Login Form */
	$("#form-login").on("submit", function(e){	
		e.preventDefault();
	
		var formData = {
			username : $("#username").val(),
			password : $("#password").val()
		};
		
		$.ajax({
			type: "post",
			url: "lib/login.php",
			data: formData,
			dataType: "json",
			encode: true,
		}).done(function(respond){
			if(respond.success){
				location.reload();
			}
			else if(!respond.success){
				$("#username").removeClass("is-invalid");
				$("#password").removeClass("is-invalid");
				
				if(respond.error.username){
					$("#username").addClass("is-invalid");
				}
				if(respond.error.password){
					$("#password").addClass("is-invalid");
				}
			}
		});
	});
});