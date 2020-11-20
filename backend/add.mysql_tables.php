<?php

echo '<hr><h4>create Tables in your MySQL Database</h4>';

define('INSTALLER', TRUE);
define('FC_PREFIX', $mig_prefs['prefs_database_prefix']);


unset($_SESSION['protocol']);
$all_tables = glob("../install/contents/*.php");
$cnt_all_tables = count($all_tables);

for($i=0;$i<$cnt_all_tables;$i++) {
	
	unset($db_path,$table_name,$table_type,$database,$cols,$sql);
	include $all_tables[$i];

	
	$query = "SELECT count(*) FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '$database_name') AND (TABLE_NAME = '$table_name')";
	$cnt_tables = $db_mysql->query($query)->fetch();
	
	$protocol .= 'PROGRESS: '.$all_tables[$i].' -'.$cnt_tables[0].'-<hr>';
	
	if($cnt_tables[0] < 1) {
		if($table_type == 'virtual' OR $database == 'index') {
			/* we only use this in index.sqlite3 */
			$protocol .= 'Jump: '.$all_tables[$i].'<hr>';
		} else {
			
			
			
			$sql = mig_generate_sql_query($all_tables[$i],'mysql');
			
			$protocol .= 'SQL for: '.print_r($sql,true);
			
			$db_mysql->query($sql);
			//echo '<pre>';
			//var_dump( $db_mysql->error() );
			//echo '</pre>';

		}			

	}

}



function mig_generate_sql_query($file,$db_type='sqlite') {
	
	include("$file");
	$string = '';
	
	if($db_type == 'sqlite') {
		/* generate sqlite query */

		foreach ($cols as $k => $v) {
			
			if(strpos($v,'INTEGER') !== false) {
				$str = 'INTEGER';
			} else if(strpos($v,'VARCHAR') !== false) {
				$str = 'VARCHAR';
			} else {
				$str = 'TEXT';
			}
			
			if(strpos($v,'PRIMARY') !== false) {
				$str .= ' NOT NULL PRIMARY KEY';
			}
			
    	$string .= "$k $str,\r";
		}
		
		$string = substr(trim("$string"), 0,-1); // cut last commata and returns
		
		if($table_type == 'virtual') {
			
			$sql_string = "CREATE VIRTUAL TABLE $table_name USING fts3($string,tokenize=porter)";
			
		} else {
			$sql_string = "
				CREATE TABLE $table_name (
				$string
				)
			";		
		}
		
	} else {
		/* generate mysql query */

		foreach ($cols as $k => $v) {
    	$string .= "$k $v,\r"; 
		}
		
		$string = substr(trim("$string"), 0,-1); // cut last commata and returns
		$table = FC_PREFIX.$table_name;
		$sql_string = "
		    CREATE TABLE $table (
		    $string
	        ) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_unicode_ci;
	    ";
		
	}

  return $sql_string;
}

?>