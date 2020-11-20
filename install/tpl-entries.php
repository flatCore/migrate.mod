<?php

/**
 * alp Database-Scheme
 * install/update the table for entries
 */

$database = "migrate";
$table_name = "entries";

$cols = array(
	"id"  => 'INTEGER NOT NULL PRIMARY KEY',
	"prefs_database_host" => 'VARCHAR',
	"prefs_database_port" => 'VARCHAR',
	"prefs_database_name" => 'VARCHAR',
	"prefs_database_username" => 'VARCHAR',
	"prefs_database_psw" => 'VARCHAR',
	"prefs_database_prefix" => 'VARCHAR'
  );
  


 
?>