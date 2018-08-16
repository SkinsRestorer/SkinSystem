<div class="col">
	<center>
		<h5>Authme database</h5>
	</center>
	<div class="row">
		<div class="form-group col-6">
			<label for="auth_host">address</label>
			<input value="localhost" type="text" id="auth_host" class="form-control">
		</div>
		<div class="form-group col-6">
			<label for="auth_port">port</label>
			<input value="3306" type="text" id="auth_port" class="form-control">
		</div>
		<div class="form-group col-6">
			<label for="auth_username">username</label>
			<input type="text" id="auth_username" class="form-control">
		</div>
		<div class="form-group col-6">
			<label for="auth_password">user password</label>
			<input type="password" id="auth_password" class="form-control">
		</div>
		<div class="form-group col-6">
			<label for="auth_database">database</label>
			<input value="authme" type="text" id="auth_database" class="form-control">
		</div>
		<div class="form-group col-6">
			<label for="auth_table">authme table</label>
			<input value="authme" type="text" id="auth_table" class="form-control">
		</div>
	</div>
	<hr>
	<div class="row" id="footer">
		<div class="col-12">
			<button type="button" class="btn btn-info" style="float:left;">Go back</button>
			<button type="button" class="btn btn-success" style="float:right;">Next step</button>
		</div>
	</div>
</div>
<script type="text/javascript">

$(document).ready(function() {
	( Cookies.get('auth_host') != ('undefined'|'') ) ? $('#auth_host').val(Cookies.get('auth_host')) : null ;
	( Cookies.get('auth_port') != ('undefined'|'') ) ? $('#auth_port').val(Cookies.get('auth_port')) : null ;
	( Cookies.get('auth_username') != ('undefined'|'') ) ? $('#auth_username').val(Cookies.get('auth_username')) : null ;
	( Cookies.get('auth_password') != ('undefined'|'') ) ? $('#auth_password').val(Cookies.get('auth_password')) : null ;
	( Cookies.get('auth_database') != ('undefined'|'') ) ? $('#auth_database').val(Cookies.get('auth_database')) : null ;
	( Cookies.get('auth_table') != ('undefined'|'') ) ? $('#auth_table').val(Cookies.get('auth_table')) : null ;
});

$('#footer').find('.btn').click(function(event) {

	let auth_host = $('#auth_host').val();
	let auth_port = $('#auth_port').val();
	let auth_username = $('#auth_username').val();
	let auth_password = $('#auth_password').val();
	let auth_database = $('#auth_database').val();
	let auth_table = $('#auth_table').val();

	$('#auth_host').css('border-color', false);
	$('#auth_port').css('border-color', false);
	$('#auth_username').css('border-color', false);
	$('#auth_password').css('border-color', false);
	$('#auth_database').css('border-color', false);
	$('#auth_table').css('border-color', false);

	if (
		( (auth_host!='')?true:$('#auth_host').css('border-color', '#a94442') )&&
		( (auth_port!='')?true:$('#auth_port').css('border-color', '#a94442') )&&
		( (auth_username!='')?true:$('#auth_username').css('border-color', '#a94442') ) &&
		( (auth_password!='')?true:$('#auth_password').css('border-color', '#a94442') ) &&
		( (auth_database!='')?true:$('#auth_database').css('border-color', '#a94442') ) &&
		( (auth_table!='')?true:$('#auth_table').css('border-color', '#a94442') )
	){
		Cookies.set('auth_host', auth_host);
		Cookies.set('auth_port', auth_port);
		Cookies.set('auth_username', auth_username);
		Cookies.set('auth_password', auth_password);
		Cookies.set('auth_database', auth_database);
		Cookies.set('auth_table', auth_table);

		console.log(Cookies.get());
		console.log('Form 2 - values okay');

		if ($(this).hasClass('btn-info')){
			window.location.href = '?step=2';
		} else if ($(this).hasClass('btn-success')) {
			window.location.href = '?step=finished';
		}

	} else {

		console.log('Form 2 - values invalid');
		toastr.options.progressBar = true;
		toastr["error"]("Some values are invalid", "Error");
	}


});

</script>
