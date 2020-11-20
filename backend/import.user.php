<?php

/* import user */

echo '<h4>user.sqlite</h4>';

if(isset($_POST['import_fc_user'])) {
	echo '<h5 id="import_fc_user">... start importing user</h5>';
	
	$dbh = new PDO("sqlite:../content/SQLite/user.sqlite3");
	$sql = "SELECT * FROM fc_user ORDER BY user_id ASC";
	$sth = $dbh->prepare($sql);
	$sth->execute();
	$old_user = $sth->fetchAll(PDO::FETCH_ASSOC);
	$dbh = null;
	
	$cnt_user = count($old_user);
	
	$all_columns = array_keys($old_user[0]);
	
	for($i=0;$i<=$cnt_user;$i++) {
		
		/* write into MySQL Database */
		
		foreach($all_columns as $col) {
			if($old_user[$i][$col] == '') {
				$old_user[$i][$col] = '';
			}
			$insert[$col] = $old_user[$i][$col];
		}
		
		$db_mysql->insert("fc_user", $insert);
		
		$user_id = $db_mysql->id();
		if($user_id > 0) {
			echo 'imported <b>'.$old_user[$i]['user_nick'].'</b><hr>';
		} else {
			echo '<pre>'.$user_id.'</pre>';
			echo '<pre>';
			var_dump( $db_mysql->error() );
			echo '</pre>';
		}
		
		
		
	}
	
}



echo '<form action="?tn=moduls&sub=migrate.mod&a=start#import_fc_user" method="POST">';

echo '<p>Import User from user.sqlite3</p>';
echo '<input type="submit" name="import_fc_user" value="Import User" class="btn btn-success">';
echo '';
echo '</form>';


	

?>