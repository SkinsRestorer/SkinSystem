<div class="col">
	<table class="table">
	  <thead class="thead-light">
		    <tr>
		      <th scope="col">Sistem settings</th>
			  <th scope="col"></th>
		    </tr>
	  </thead>
	  	<tbody>
		  	<tr id="sys_name">
				<td>sys_name</td>
				<td>val</td>
		  	</tr>
		  	<tr id="sys_skinhistory">
				<td>sys_skinhistory</td>
				<td>val</td>
		  	</tr>
		  	<tr id="sys_ispublic">
				<td>sys_ispublic</td>
				<td>val</td>
		  	</tr>
  		</tbody>
	</table>
	<table class="table">
	  <thead class="thead-light">
		    <tr>
		      <th scope="col">Authme settings</th>
			  <th scope="col"></th>
		    </tr>
	  </thead>
	  	<tbody>
		  	<tr id="auth_enabled">
				<td>auth_enabled</td>
				<td>val</td>
		  	</tr>
		  	<tr id="auth_host">
				<td>auth_host</td>
				<td>val</td>
		  	</tr>
		  	<tr id="auth_port">
				<td>auth_port</td>
				<td>val</td>
		  	</tr>
		  	<tr id="auth_username">
				<td>auth_username</td>
				<td>val</td>
		  	</tr>
		  	<tr id="auth_password">
				<td>auth_password</td>
				<td>val</td>
		  	</tr>
		  	<tr id="auth_database">
				<td>auth_database</td>
				<td>val</td>
		  	</tr>
		  	<tr id="auth_table">
				<td>auth_table</td>
				<td>val</td>
		  	</tr>
  		</tbody>
	</table>
	<table class="table">
	  <thead class="thead-light">
		    <tr>
		      <th scope="col">Skinsrestorer settings</th>
			  <th scope="col"></th>
		    </tr>
	  </thead>
	  	<tbody>
		  	<tr id="sr_host">
				<td>sr_host</td>
				<td>val</td>
		  	</tr>
		  	<tr id="sr_port">
				<td>sr_port</td>
				<td>val</td>
		  	</tr>
		  	<tr id="sr_username">
				<td>sr_username</td>
				<td>val</td>
		  	</tr>
		  	<tr id="sr_password">
				<td>sr_password</td>
				<td>val</td>
		  	</tr>
		  	<tr id="sr_database">
				<td>sr_database</td>
				<td>val</td>
		  	</tr>
		  	<tr id="sr_tbl_skins">
				<td>sr_tbl_skins</td>
				<td>val</td>
		  	</tr>
		  	<tr id="sr_tbl_players">
				<td>sr_tbl_players</td>
				<td>val</td>
		  	</tr>
  		</tbody>
	</table>
	<hr>
	<div class="row" id="footer">
		<div class="col-12">
			<button type="button" class="btn btn-info" style="float:left;">Go back</button>
			<button type="button" class="btn btn-success" style="float:right;">Finish setup</button>
		</div>
	</div>
</div>
<script type="text/javascript">

$(document).ready(function() {

let cookies = Cookies.get();
console.log(cookies);

{
	$('#sys_name').children('td:eq(1)').html(Cookies.get('sys_name'));
	$('#sys_skinhistory').children('td:eq(1)').html(Cookies.get('sys_skinhistory'));
	$('#sys_ispublic').children('td:eq(1)').html(Cookies.get('sys_ispublic'));

	$('#auth_enabled').children('td:eq(1)').html(Cookies.get('auth_enabled'));
	$('#auth_host').children('td:eq(1)').html(Cookies.get('auth_host'));
	$('#auth_port').children('td:eq(1)').html(Cookies.get('auth_port'));
	$('#auth_username').children('td:eq(1)').html(Cookies.get('auth_username'));
	$('#auth_password').children('td:eq(1)').html(Cookies.get('auth_password'));
	$('#auth_database').children('td:eq(1)').html(Cookies.get('auth_database'));
	$('#auth_table').children('td:eq(1)').html(Cookies.get('auth_table'));

	$('#sr_host').children('td:eq(1)').html(Cookies.get('sr_host'));
	$('#sr_port').children('td:eq(1)').html(Cookies.get('sr_port'));
	$('#sr_username').children('td:eq(1)').html(Cookies.get('sr_username'));
	$('#sr_password').children('td:eq(1)').html(Cookies.get('sr_password'));
	$('#sr_database').children('td:eq(1)').html(Cookies.get('sr_database'));
	$('#sr_tbl_skins').children('td:eq(1)').html(Cookies.get('sr_tbl_skins'));
	$('#sr_tbl_players').children('td:eq(1)').html(Cookies.get('sr_tbl_players'));
}

if ( false ){

	Cookies.remove('sys_name');
	Cookies.remove('sys_skinhistory');
	Cookies.remove('sys_ispublic');

	Cookies.remove('auth_enabled');
	Cookies.remove('auth_host');
	Cookies.remove('auth_port');
	Cookies.remove('auth_username');
	Cookies.remove('auth_password');
	Cookies.remove('auth_database');
	Cookies.remove('auth_table');

	Cookies.remove('sr_host');
	Cookies.remove('sr_port');
	Cookies.remove('sr_username');
	Cookies.remove('sr_password');
	Cookies.remove('sr_database');
	Cookies.remove('sr_tbl_skins');
	Cookies.remove('sr_tbl_players');
}
});

$('#footer').find('.btn.btn-info').click(function(event) {
	if (Cookies.get('sys_ispublic') == (true|'true')){
		window.location.href = '?step=1';
	} else {
		if (Cookies.get('auth_enabled') == (true|'true')){
			window.location.href = '?step=2';
		} else {
			window.location.href = '?step=3';
		}
	}
});
$('#footer').find('.btn.btn-success').click(function(event) {

});
</script>
