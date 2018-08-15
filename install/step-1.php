<div class="col">
	<div class="row">
		<div class="form-group col-6">
			<label for="sys-name">Server name</label>
			<input placeholder="play.myserver.net" type="text" id="sys-name" class="form-control">
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="form-group col-6">
			<?php
				if ( is_writable(__DIR__ . '/../lib') ){
					echo '<p style="color:green;font-weight:bold;" id="rwxperms">True - directory is writable :)</p><script>Cookies.set("sys_perms", "true");</script>';
				} else {
					echo '<p style="color:red;font-weight:bold;" id="rwxperms">False - directory is NOT writable! Make it so.</p><script>Cookies.set("sys_perms", "false");</script>';
				}
			?>
		</div>
		<div class="col-6">
			<p>/lib/ directory must be writable.</p>
			<p>If you are on linux (which you should totally be), simply run one of the following commands, where "folderPath" is the path to "lib" directory:</p>
			<p class="code">chown www-data:www-data folderPath <br> chmod 775 folderPath</p>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="form-group col-6">
			<label for="sys-skinhistory">Should authme hook be enabled?</label>
			<br>
			<input type="checkbox" data-toggle="toggle" data-on="Enabled" data-off="Disabled" id="auth_enabled">
		</div>
		<div class="col-6">
			<p>Do you want to allow users to only login and manage accounts that they have access to? This option is higly recomended.</p>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="form-group col-6">
			<label for="sys-skinhistory">Should skin history be enabled?</label>
			<br>
			<input type="checkbox" data-toggle="toggle" data-on="Enabled" data-off="Disabled" id="sys-skinhistory">
		</div>
		<div class="col-6">
			<p>This will store a little more data in your MySql database, nothing major though ^-^</p>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="form-group col-6">
			<label for="sys_isprivate">Is system for public or private use?</label>
			<br>
			<input type="checkbox" data-toggle="toggle" data-on="Public" data-off="Private" id="sys_isprivate">
		</div>
		<div class="col-6">
			<p>Currently not in use // will be used in future update(-s)</p>
			<p>If enabled, no database data will not be saved and you will have to provide it every time you use this software</p>
		</div>
	</div>
	<hr>
	<div class="row" id="footer">
		<div class="col-12">
			<button type="button" class="btn btn-info" style="float:left;" disabled>Go back</button>
			<button type="button" class="btn btn-success" style="float:right;">Next step</button>
		</div>
	</div>
</div>
<style media="all">
	.code {
		background-color: rgb(230, 230, 230);
		padding: 3px;
		border-color: rgb(100, 100, 100);
		border-radius: 5px;
		border-width: 2px;
	}
</style>
<script type="text/javascript">


$(document).ready(function() {

	( Cookies.get('sys_name') != ('undefined'|'') ) ? $('#sys-name').val(Cookies.get('sys_name')) : null ;

	( Cookies.get('auth_enabled') == ('true'||true) ) ? $('#auth_enabled').prop('checked', true).change() : null ;

	( Cookies.get('sys_skinhistory') == ('true'||true) ) ? $('#sys-skinhistory').prop('checked', true).change() : null ;

	( Cookies.get('sys_ispublic') == ('true'||true) ) ? $('#sys_isprivate').prop('checked', true).change() : null ;


	{
		let auth_enabled_state = $('#auth_enabled').prop('checked');
		let sys_isprivate_state = $('#sys_isprivate').prop('checked');

		$('#auth_enabled').change(function() {
			auth_enabled_state = $('#auth_enabled').prop('checked');
		});
		$('#sys_isprivate').change(function() {
			sys_isprivate_state = $('#sys_isprivate').prop('checked');

			if ( $(this).prop('checked') == true ){
				$('#auth_enabled').prop('checked', false).change();
				$('#auth_enabled').prop('disabled', true);
				$('#auth_enabled').parents('.btn').css('background-color', '#fdd5d5');
			} else {
				$('#auth_enabled').prop('disabled', false);
				$('#auth_enabled').parents('.btn').css('background-color', '');
				$('#auth_enabled').prop('checked', auth_enabled_state).change();
			}

		});
	}

});

$('#footer').find('.btn.btn-success').click(function(event) {

	let srvname = $('#sys-name').val();
	let auth_enabled = ( $('#auth_enabled').prop('checked') == ('true'|true) ) ? true : false ;
	let skinhistory = ( $('#sys-skinhistory').prop('checked') == ('true'|true) ) ? true : false ;
	let ispublic = ( $('#sys_isprivate').prop('checked') == ('true'|true) ) ? true : false ;

	$('#sys-name').css('border-color', false);

	if ( (srvname!='') && (skinhistory===true|skinhistory===false) && (ispublic===true|ispublic===false) && (auth_enabled===true|auth_enabled===false) ){
		Cookies.set('sys_name', srvname);
		Cookies.set('auth_enabled', auth_enabled);
		Cookies.set('sys_skinhistory', skinhistory);
		Cookies.set('sys_ispublic', ispublic);
		console.log(Cookies.get());
		console.log('Form 1 - values okay');

		if (ispublic===true) {
			window.location.href = '?step=finished';
		} else {
			window.location.href = '?step=2';
		}

	} else {
		if (srvname==''){
			$('#sys-name').css('border-color', '#a94442');
		}

		console.log('Form 1 - values invalid');
		toastr.options.progressBar = true;
		toastr["error"]("Some values are invalid", "Error");
	}


});

</script>
