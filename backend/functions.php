<?php


function mig_get_preferences() {
	
	global $mod_db;
	global $mod;
	
	$dbh = new PDO("sqlite:$mod_db");
	$sql = "SELECT * FROM prefs WHERE prefs_status LIKE '%active%' ";
	$prefs = $dbh->query($sql);
	$prefs = $prefs->fetch(PDO::FETCH_ASSOC);
	$dbh = null;
	
	
	
	return $prefs;
	
}


?>