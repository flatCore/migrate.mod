<?php

/* import posts */

echo '<h4>posts.sqlite</h4>';

if(isset($_POST['import_fc_posts'])) {
	echo '<h5 id="import_fc_posts">... start importing posts</h5>';
	
	$dbh = new PDO("sqlite:../content/SQLite/posts.sqlite3");
	$sql = "SELECT * FROM fc_posts ORDER BY post_id ASC";
	$sth = $dbh->prepare($sql);
	$sth->execute();
	$old_posts = $sth->fetchAll(PDO::FETCH_ASSOC);
	$dbh = null;
	
	$cnt_posts = count($old_posts);
	
	$all_columns = array_keys($old_posts[0]);
	
	for($i=0;$i<=$cnt_posts;$i++) {
		
		/* write into MySQL Database */
		
		foreach($all_columns as $col) {
			if($old_posts[$i][$col] == '') {
				$old_posts[$i][$col] = '';
			}
			$insert[$col] = $old_posts[$i][$col];
		}
		
		$db_mysql->insert("fc_posts", $insert);
		
		$post_id = $db_mysql->id();
		if($post_id > 0) {
			echo 'imported <b>'.$old_posts[$i]['post_title'].'</b><hr>';
		} else {
			echo '<pre>'.$post_id.'</pre>';
			echo '<pre>';
			var_dump( $db_mysql->error() );
			echo '</pre>';
		}
		
		
		
	}
	
}



echo '<form action="?tn=moduls&sub=migrate.mod&a=start#import_fc_posts" method="POST">';

echo '<p>Import Posts from posts.sqlite3</p>';
echo '<input type="submit" name="import_fc_posts" value="Import Posts" class="btn btn-success">';
echo '';
echo '</form>';


	

?>