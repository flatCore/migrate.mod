<?php

/**
 * cookies | flatCore Modul
 * Configuration File
 */

if(FC_SOURCE == 'backend') {
	$mod_root = '../modules/';
} else {
	$mod_root = 'modules/';
}

$mod = array(
	"name" => "migrate",
	"version" => "0.1.1",
	"author" => "flatCore.org",
	"description" => "Migrate databases - SQLite <-> MySQL",
	"database" => "content/SQLite/migrate.sqlite3"
);


/* acp navigation */
$modnav[] = array('link' => 'Overview', 'title' => 'Migrate overview', 'file' => "start");

?>