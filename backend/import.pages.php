<?php

/* import pages, preferences */

echo '<h4>content.sqlite</h4>';

if(isset($_POST['import_content'])) {

	$pages = import_contents_from_sqlite('fc_pages','page_id');
	$pages_cache = import_contents_from_sqlite('fc_pages_cache','page_id');
	$prefs = import_contents_from_sqlite('fc_preferences','prefs_id');
	$textlib = import_contents_from_sqlite('fc_textlib','textlib_id');
	$addons = import_contents_from_sqlite('fc_addons','addon_id');
	$cats = import_contents_from_sqlite('fc_categories','cat_id');
	$comments = import_contents_from_sqlite('fc_comments','comment_id');
	$feeds = import_contents_from_sqlite('fc_feeds','feed_id');
	$labels = import_contents_from_sqlite('fc_labels','label_id');
	$media = import_contents_from_sqlite('fc_media','media_id');
}




function import_contents_from_sqlite($table_name,$id_column) {
	
	global $db_mysql;
	
	$dbh = new PDO("sqlite:../content/SQLite/content.sqlite3");
	$sql = "SELECT * FROM $table_name ORDER BY $id_column ASC";
	$sth = $dbh->prepare($sql);
	$sth->execute();
	$get_data = $sth->fetchAll(PDO::FETCH_ASSOC);
	$dbh = null;
	
	
	$cnt_data = count($get_data);
	
	echo "<p>FOUND $cnt_data ROWS in $table_name</p>";
	
	$all_columns = array_keys($get_data[0]);
	
	for($i=0;$i<=$cnt_data;$i++) {
		
		/* write into MySQL Database */
		
		foreach($all_columns as $col) {
			if($get_data[$i][$col] == '') {
				$get_data[$i][$col] = '';
			}
			$insert[$col] = $get_data[$i][$col];
		}
		
		$db_mysql->insert("$table_name", $insert);
		
		$user_id = $db_mysql->id();
		if($user_id > 0) {
			//echo 'imported <b>'.$get_data[$i]['page_title'].'</b><hr>';
		} else {
			echo '<pre>'.$user_id.'</pre>';
			echo '<pre>';
			var_dump( $db_mysql->error() );
			echo '</pre>';
		}
		
		
		
	}	
	
}




echo '<form action="?tn=moduls&sub=migrate.mod&a=start#import_content" method="POST">';

echo '<p>Import Data from content.sqlite3</p>';
echo '<input type="submit" name="import_content" value="Import Content" class="btn btn-success">';
echo '<input type="hidden" name="csrf_token" value="'.$_SESSION['token'].'">';
echo '</form>';


	

?>