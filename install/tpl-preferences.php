<?php

/**
 * migrate Database-Scheme
 * install/update the table for preferences
 */

$database = "migrate";
$table_name = "prefs";

$cols = array(
	"prefs_id"  => 'INTEGER NOT NULL PRIMARY KEY',
	"prefs_status"  => 'VARCHAR',
	"prefs_version" => 'VARCHAR',
	"prefs_modus" => 'VARCHAR',
	"prefs_database_host" => 'VARCHAR',
	"prefs_database_port" => 'VARCHAR',
	"prefs_database_name" => 'VARCHAR',
	"prefs_database_username" => 'VARCHAR',
	"prefs_database_psw" => 'VARCHAR',
	"prefs_database_prefix" => 'VARCHAR'
  );
  
  
 
?>
