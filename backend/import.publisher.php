<?php

/* import publisher stuff */

echo '<h4>publisher.mod</h4>';

if(isset($_POST['import_pub_cats'])) {
	echo '<h5 id="import_pub_cats">... start importing categories</h5>';
	
	$dbh = new PDO("sqlite:../content/SQLite/publisher.sqlite3");
	$sql = "SELECT * FROM categories ORDER BY sort ASC";
	$sth = $dbh->prepare($sql);
	$sth->execute();
	$categories = $sth->fetchAll(PDO::FETCH_ASSOC);
	$dbh = null;
	
	$cnt_cats = count($categories);
	
	if($_POST['database'] == 'mysql') {
		$target_db = $db_mysql;
	} else {
		$target_db = $db_content;
	}
	
	
	for($i=0;$i<=$cnt_cats;$i++) {
		
		$cat_name = $categories[$i]['name'];
		$cat_name_clean = clean_filename($cat_name);
		$cat_sort = $categories[$i]['sort'];
		$cat_description = $categories[$i]['description'];
		$cat_thumbnail = $categories[$i]['thumbnail'];
		
		if($cat_name == '' OR $cat_name_clean == '') {
			continue;
		}
		
		/* write into flatCore Database */
		$data = $target_db->insert("fc_categories", [
			"cat_name" =>  "$cat_name",
			"cat_name_clean" =>  "$cat_name_clean",
			"cat_sort" =>  "$cat_sort",
			"cat_description" =>  "$cat_description",
			"cat_thumbnail" =>  "$cat_thumbnail"
		]);
		
		echo '<p>imported <b>'.$cat_name.'</b></p>';		
	}
	
}


if(isset($_POST['import_pub_entries'])) {
	
	$dbh = new PDO("sqlite:../content/SQLite/publisher.sqlite3");
	$sql = "SELECT * FROM posts ORDER BY id ASC";
	$sth = $dbh->prepare($sql);
	$sth->execute();
	$entries = $sth->fetchAll(PDO::FETCH_ASSOC);
	$dbh = null;
	
	$cnt_entries = count($entries);
	
	if($_POST['database'] == 'mysql') {
		$target_db = $db_mysql;
	} else {
		$target_db = $db_posts;
	}
	

	$last_id = $entries[$cnt_entries-1]['id'];
	// $last_id is the number of entries created in publisher
	// we create now empty entries in the new databese according to $last_id
	// we set 'type' to auto_created, so we can identify the rows later
	// delete the unused (auto_created) rows
	
	for($i=0;$i<=$last_id;$i++) {
		$data = $target_db->insert("fc_posts", [
				"post_type" =>  "auto_created"
		]);
	}
	
	
	echo '<h5 id="import_pub_entries">... start importing (we found '.$cnt_entries.' entries)</h5>';
	
	$all_columns = array_keys($entries[0]);
	
	for($i=0;$i<=$cnt_entries;$i++) {
		
			$type = substr($entries[$i]['type'],0,1);
			
			if($entries[$i]['priority'] == 'fixed') {
				$fixed = 1;
			} else {
				$fixed = 2;
			}
			
			if($entries[$i]['status'] == 'published') {
				$status = 1;
			} else {
				$status = 2;
			}
			
			foreach($all_columns as $col) {
				if($entries[$i][$col] == '' OR $entries[$i][$col] == NULL) {
					$entries[$i][$col] = ' ';
				}
			}
			
			
			/* switch category from clean_name to id */
			$cat_id = $target_db->get("fc_categories","cat_id",[
				"cat_name_clean" => $entries[$i]['categories']
			]);
			
			if($cat_id == '') {
				$cat_id = '';
			}
			
			
			$data = $target_db->update("fc_posts", [
				"post_type" =>  "$type",
				"post_date" =>  $entries[$i]['date'],
				"post_releasedate" =>  $entries[$i]['releasedate'],
				"post_lastedit" =>  $entries[$i]['lastedit'],
				"post_title" =>  $entries[$i]['title'],
				"post_teaser" =>  $entries[$i]['teaser'],
				"post_text" =>  $entries[$i]['text'],
				"post_images" =>  $entries[$i]['images'],
				"post_lang" =>  $entries[$i]['lang'],
				"post_link" =>  $entries[$i]['link'],
				"post_video_url" =>  $entries[$i]['video_url'],
				"post_tags" =>  $entries[$i]['tags'],
				"post_categories" =>  $cat_id,
				"post_slug" =>  $entries[$i]['slug'],
				"post_priority" =>  $entries[$i]['priority'],
				"post_fixed" =>  $fixed,
				"post_status" =>  $status,
				"post_author" =>  $entries[$i]['author'],
				"post_event_startdate" =>  $entries[$i]['startdate'],
				"post_event_enddate" =>  $entries[$i]['enddate'],
				"post_event_zip" =>  $entries[$i]['event_zip'],
				"post_event_city" =>  $entries[$i]['event_city'],
				"post_event_street" =>  $entries[$i]['event_street'],
				"post_event_street_nbr" =>  $entries[$i]['event_street_nbr'],
				"post_event_price_note" =>  $entries[$i]['event_price_note'],
				"post_product_number" =>  $entries[$i]['product_number'],
				"post_product_manufacturer" =>  $entries[$i]['product_manufacturer'],
				"post_product_supplier" =>  $entries[$i]['product_supplier'],
				"post_product_tax" =>  $entries[$i]['product_tax'],
				"post_product_price_net" =>  $entries[$i]['product_price_net'],
				"post_product_price_label" =>  $entries[$i]['product_price_label'],
				"post_product_textlib_price" =>  $entries[$i]['product_textlib_price'],
				"post_product_textlib_content" =>  $entries[$i]['product_textlib_content'],
				"post_product_currency" =>  $entries[$i]['product_currency'],
				"post_product_unit" =>  $entries[$i]['product_unit'],
				"post_product_amount" =>  $entries[$i]['product_amount'],
			],[
				"post_id" => $entries[$i]['id']
			]);
		
		 	$data->rowCount();
		 	
			if($data < 1) {
				echo '<pre>'.$entries[$i]['id'].'</pre>';
				echo '<pre>';
				var_dump($target_db->error());
				echo '</pre>';
			}
			
		}

		$target_db->delete("fc_posts", [
			"post_type" => "auto_created"
		]);
}

echo '<div class="row">';
echo '<div class="col-md-6">';

echo '<form action="?tn=moduls&sub=migrate.mod&a=start#import_pub_cats" method="POST">';
echo '<p>Import the <strong>Categories</strong> from publisher.mod to flatCore</p>';
echo '<div class="form-group">';
echo '<label>Choose your target database</label>';
echo '<select name="database" class="form-control custom-select">';
echo '<option value="mysql">import in MySQL</option>';
echo '<option value="sqlite">import in content.sqlite3</option>';
echo '</select>';
echo '</div>';
echo '<input type="submit" name="import_pub_cats" value="Import Categories" class="btn btn-success">';
echo '<input type="hidden" name="csrf_token" value="'.$_SESSION['token'].'">';
echo '</form>';

echo '</div>';
echo '<div class="col-md-6">';

echo '<form action="?tn=moduls&sub=migrate.mod&a=start#import_pub_entries" method="POST">';

echo '<p>Import the <strong>Entries</strong> from publisher.mod to flatCore</p>';
echo '<div class="form-group">';
echo '<label>Choose your target database</label>';
echo '<select name="database" class="form-control custom-select">';
echo '<option value="mysql">import in MySQL</option>';
echo '<option value="sqlite">import in posts.sqlite3</option>';
echo '</select>';
echo '</div>';
echo '<input type="submit" name="import_pub_entries" value="Import Entries" class="btn btn-success">';
echo '<input type="hidden" name="csrf_token" value="'.$_SESSION['token'].'">';
echo '</form>';

echo '</div>';
echo '</div>';
	

?>