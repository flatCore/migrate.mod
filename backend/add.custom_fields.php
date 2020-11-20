<?php

/* check if we have in user/content.sqlite3 custom fields */
$database_name = $mig_prefs['prefs_database_name'];



/* add custom field to fc_pages and fc_pages_cache */
if(isset($_GET['new_custom_page'])) {
	
	$new_custom_page = $_GET['new_custom_page'];
	
	$sql = "ALTER TABLE fc_pages ADD $new_custom_page LONGTEXT";
	if($db_mysql->query($sql)) {
		echo '<p>ALTER TABLE DONE '.$new_custom_page.'</p>';
	} else {
		var_dump( $db_mysql->error() );
	}
	
	$sql = "ALTER TABLE fc_pages_cache ADD $new_custom_page LONGTEXT";
	if($db_mysql->query($sql)) {
		echo '<p>ALTER TABLE DONE '.$new_custom_page.'</p>';
	} else {
		var_dump( $db_mysql->error() );
	}
	
}

/* add custom field to fc_user */

if(isset($_GET['new_custom_user'])) {
	
	$new_custom_user = $_GET['new_custom_user'];
	
	$sql = "ALTER TABLE fc_user ADD $new_custom_user LONGTEXT";
	if($db_mysql->query($sql)) {
		echo '<p>ALTER TABLE DONE '.$new_custom_user.'</p>';
	} else {
		var_dump( $db_mysql->error() );
	}
	
}







$custom_content = get_custom_fields();

if(count($custom_content) > 0) {
	foreach($custom_content as $new) {
		
		$query = "SELECT count(*) FROM information_schema.COLUMNS WHERE (TABLE_SCHEMA = '$database_name') AND (TABLE_NAME = 'fc_pages') AND (COLUMN_NAME = '$new')";
		$cnt_tables = $db_mysql->query($query)->fetch();		
		if($cnt_tables[0]>0) {
			echo '<p class="text-success">Column '.$new.' exists in fc_pages ...</p>';	
		} else {
			echo '<p>ADD <a href="?tn=moduls&sub=migrate.mod&a=start&new_custom_page='.$new.'">'. $new.'</a> to fc_pages table</p>';
		}
		
	}
} else {
	echo '<p>No Custom Columns in fc_pages</p>';
}

$custom_user = get_custom_user_fields();
if(count($custom_user) > 0) {
	foreach($custom_user as $new) {
		
		$query = "SELECT count(*) FROM information_schema.COLUMNS WHERE (TABLE_SCHEMA = '$database_name') AND (TABLE_NAME = 'fc_user') AND (COLUMN_NAME = '$new')";
		$cnt_tables = $db_mysql->query($query)->fetch();		
		if($cnt_tables[0]>0) {
			echo '<p class="text-success">Column '.$new.' exists in fc_user ...</p>';	
		} else {
			echo '<p>ADD <a href="?tn=moduls&sub=migrate.mod&a=start&new_custom_user='.$new.'">'. $new.'</a> to fc_user table</p><hr>';
		}
	}
} else {
	echo '<p>No Custom Columns in fc_user</p>';
}







?>