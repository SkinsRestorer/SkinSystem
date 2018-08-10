<div class="col">
	<center>
		<h5>Skinsrestorer's database</h5>
	</center>
	<div class="row">
		<div class="form-group col-6">
			<label for="sys-name">address</label>
			<input value="localhost" type="text" id="sr_host" class="form-control">
		</div>
		<div class="form-group col-6">
			<label for="sys-name">port</label>
			<input value="3306" type="text" id="sr_port" class="form-control">
		</div>
		<div class="form-group col-6">
			<label for="sys-name">username</label>
			<input type="text" id="sr_username" class="form-control">
		</div>
		<div class="form-group col-6">
			<label for="sys-name">user password</label>
			<input type="text" id="sr_password" class="form-control">
		</div>
		<div class="form-group col-6">
			<label for="sys-name">database</label>
			<input value="skinsrestorer" type="text" id="sr_database" class="form-control">
		</div>
		<div class="form-group col-6">
			<label for="sys-name">player table</label>
			<input value="players" type="text" id="sr_tbl_skins" class="form-control">
		</div>
		<div class="form-group col-6">
			<label for="sys-name">skins table</label>
			<input value="skins" type="text" id="sr_tbl_players" class="form-control">
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
	( Cookies.get('sr_host') != 'undefined' ) ? $('#sr_host').val(Cookies.get('sr_host')) : null ;
	( Cookies.get('sr_port') != 'undefined' ) ? $('#sr_port').val(Cookies.get('sr_port')) : null ;
	( Cookies.get('sr_username') != 'undefined' ) ? $('#sr_username').val(Cookies.get('sr_username')) : null ;
	( Cookies.get('sr_password') != 'undefined' ) ? $('#sr_password').val(Cookies.get('sr_password')) : null ;
	( Cookies.get('sr_database') != 'undefined' ) ? $('#sr_database').val(Cookies.get('sr_database')) : null ;
	( Cookies.get('sr_tbl_skins') != 'undefined' ) ? $('#sr_tbl_skins').val(Cookies.get('sr_tbl_skins')) : null ;
	( Cookies.get('sr_tbl_players') != 'undefined' ) ? $('#sr_tbl_players').val(Cookies.get('sr_tbl_players')) : null ;
});

$('#footer').find('.btn').click(function(event) {

	let sr_host = $('#sr_host').val();
	let sr_port = $('#sr_port').val();
	let sr_username = $('#sr_username').val();
	let sr_password = $('#sr_password').val();
	let sr_database = $('#sr_database').val();
	let sr_tbl_skins = $('#sr_tbl_skins').val();
	let sr_tbl_players = $('#sr_tbl_players').val();

	$('#sr_host').css('border-color', false);
	$('#sr_port').css('border-color', false);
	$('#sr_username').css('border-color', false);
	$('#sr_password').css('border-color', false);
	$('#sr_database').css('border-color', false);
	$('#sr_tbl_skins').css('border-color', false);
	$('#sr_tbl_players').css('border-color', false);

	if (
		( (sr_host!='')?true:$('#sr_host').css('border-color', '#a94442') )&&
		( (sr_port!='')?true:$('#sr_port').css('border-color', '#a94442') )&&
		( (sr_username!='')?true:$('#sr_username').css('border-color', '#a94442') )&&
		( (sr_password!='')?true:$('#sr_password').css('border-color', '#a94442') )&&
		( (sr_database!='')?true:$('#sr_database').css('border-color', '#a94442') )&&
		( (sr_tbl_skins!='')?true:$('#sr_tbl_skins').css('border-color', '#a94442') )&&
		( (sr_tbl_players!='')?true:$('#sr_tbl_players').css('border-color', '#a94442') )
	){
		Cookies.set('sr_host', sr_host);
		Cookies.set('sr_port', sr_port);
		Cookies.set('sr_username', sr_username);
		Cookies.set('sr_password', sr_password);
		Cookies.set('sr_database', sr_database);
		Cookies.set('sr_tbl_skins', sr_tbl_skins);
		Cookies.set('sr_tbl_players', sr_tbl_players);

		console.log(Cookies.get());
		console.log('Form 2 - values okay');

		if ($(this).hasClass('btn-info')){
			window.location.href = '?step=1';
		} else if ($(this).hasClass('btn-success')) {
			if ( Cookies.get('auth_enabled') == ('true'||true) ){
				window.location.href = '?step=3';
			} else {
				window.location.href = '?step=finished';
			}
		}

	} else {

		console.log('Form 2 - values invalid');
		toastr.options.progressBar = true;
		toastr["error"]("Some values are invalid", "Error");
	}


});

</script>
