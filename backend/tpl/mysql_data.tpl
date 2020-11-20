<fieldset class="mt-4">
	<legend>MySQL Data</legend>
<form action="{formaction}" method="POST">
	<div class="form-group row">	
		<label class="col-sm-3 control-label text-right">{lang_db_host}</label>
			<div class="col-sm-9">
				<input type="text" class="form-control" name="prefs_database_host" placeholder="localhost" value="{prefs_database_host}">
				<small class="form-text text-muted">{lang_db_host_help}</small>
		</div>
	</div>
						
	<div class="form-group row">
		<label class="col-sm-3 control-label text-right">{lang_db_port}</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" name="prefs_database_port" value="{prefs_database_port}">
			<small class="form-text text-muted">{lang_db_port_help}</small>
		</div>
	</div>
						
	<div class="form-group row">
		<label class="col-sm-3 control-label text-right">{lang_db_name}</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" name="prefs_database_name" value="{prefs_database_name}">
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-3 control-label text-right">{lang_db_username}</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" name="prefs_database_username" value="{prefs_database_username}">
			<small class="form-text text-muted">{lang_db_username_help}</small>
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-3 control-label text-right">{lang_db_psw}</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" name="prefs_database_psw" value="{prefs_database_psw}">
			<small class="form-text text-muted">{lang_db_psw_help}</small>
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-3 control-label text-right">{lang_db_prefix}</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" name="prefs_database_prefix" placeholder="fcdb_" value="{prefs_database_prefix}">
			<small class="form-text text-muted">{lang_db_prefix_help}</small>
		</div>
	</div>
	
	<div class="form-group row justify-content-end">
		<div class="col-sm-9">
			<button class="btn btn-fc" name="update" value="update_mysql">save data</button>
			<button class="btn btn-fc float-right" name="generate_config_file" value="generate">Generate config file</button>
		</div>
	</div>
	<input type="hidden" name="csrf_token" value="{token}">
</form>

</fieldset>