<?php
error_reporting(E_ALL ^E_NOTICE);
define('INSTALLER', TRUE);
if(!defined('FC_INC_DIR')) {
	die("No access");
}

use Medoo\Medoo;

if(is_file("../lib/lang/$languagePack/dict-install.php")) {
	include "../lib/lang/$languagePack/dict-install.php";
}

include '../modules/'.$mod_name.'.mod/install/installer.php';
include '../modules/'.$mod_name.'.mod/backend/functions.php';
include '../install/php/functions.php';


echo '<h3>'.$mod_name.' '.$mod_version.' <small>| '.$mod['description'].'</small></h3>';

echo '<div class="alert alert-danger">';
echo '<ul>';
echo '<li><strong>It is extremely important</strong> to delete this addon and the associated file <code>(migrate.sqlite3)</code> from your server after the migration is done.</li>';
echo '<li>Before you start, make sure you have the latest version installed (flatCore <strong>and</strong> migrate.mod).</li>';
echo '</ul>';
echo '</div>';


if(isset($_POST['update']) && ($_POST['update'] == 'update_mysql')) {
	$dbh = new PDO("sqlite:$mod_db");
	
	$sql_update = "UPDATE prefs
				SET	prefs_database_host = :prefs_database_host,
					prefs_database_port = :prefs_database_port,
					prefs_database_name = :prefs_database_name,
					prefs_database_username = :prefs_database_username,
					prefs_database_psw = :prefs_database_psw,
					prefs_database_prefix = :prefs_database_prefix
				WHERE prefs_status = 'active' ";
	
	$sth = $dbh->prepare($sql_update);
	$sth->bindParam(':prefs_database_host', $_POST['prefs_database_host'], PDO::PARAM_STR);
	$sth->bindParam(':prefs_database_port', $_POST['prefs_database_port'], PDO::PARAM_STR);
	$sth->bindParam(':prefs_database_name', $_POST['prefs_database_name'], PDO::PARAM_STR);
	$sth->bindParam(':prefs_database_username', $_POST['prefs_database_username'], PDO::PARAM_STR);
	$sth->bindParam(':prefs_database_psw', $_POST['prefs_database_psw'], PDO::PARAM_STR);
	$sth->bindParam(':prefs_database_prefix', $_POST['prefs_database_prefix'], PDO::PARAM_STR);
	
	$cnt_changes = $sth->execute();
	
				
	$dbh = NULL;
}



$mig_prefs = mig_get_preferences();

$form_tpl = file_get_contents(__DIR__.'/tpl/mysql_data.tpl');

$form_tpl = str_replace("{lang_db_host}",$lang['db_host'],$form_tpl);
$form_tpl = str_replace("{lang_db_port}",$lang['db_port'],$form_tpl);
$form_tpl = str_replace("{lang_db_name}",$lang['db_name'],$form_tpl);
$form_tpl = str_replace("{lang_db_username}",$lang['db_username'],$form_tpl);
$form_tpl = str_replace("{lang_db_psw}",$lang['db_psw'],$form_tpl);
$form_tpl = str_replace("{lang_db_prefix}",$lang['db_prefix'],$form_tpl);

$form_tpl = str_replace("{lang_db_host_help}",$lang['db_host_help'],$form_tpl);
$form_tpl = str_replace("{lang_db_port_help}",$lang['db_port_help'],$form_tpl);
$form_tpl = str_replace("{lang_db_username_help}",$lang['db_username_help'],$form_tpl);
$form_tpl = str_replace("{lang_db_psw_help}",$lang['db_psw_help'],$form_tpl);
$form_tpl = str_replace("{lang_db_prefix_help}",$lang['db_prefix_help'],$form_tpl);

$form_tpl = str_replace("{prefs_database_host}",$mig_prefs['prefs_database_host'],$form_tpl);
$form_tpl = str_replace("{prefs_database_port}",$mig_prefs['prefs_database_port'],$form_tpl);
$form_tpl = str_replace("{prefs_database_name}",$mig_prefs['prefs_database_name'],$form_tpl);
$form_tpl = str_replace("{prefs_database_username}",$mig_prefs['prefs_database_username'],$form_tpl);
$form_tpl = str_replace("{prefs_database_psw}",$mig_prefs['prefs_database_psw'],$form_tpl);
$form_tpl = str_replace("{prefs_database_prefix}",$mig_prefs['prefs_database_prefix'],$form_tpl);

$form_tpl = str_replace("{token}",$_SESSION['token'],$form_tpl);
$form_tpl = str_replace("{formaction}",'?tn=moduls&sub=migrate.mod&a=start',$form_tpl);


echo '<div class="row">';
echo '<div class="col-md-6">';
echo $form_tpl;
echo '</div>';
echo '<div class="col-md-6">';

if($mig_prefs['prefs_database_name'] != '' || $mig_prefs['prefs_database_username'] != '' && $mig_prefs['prefs_database_psw'] != '') {
	
	try {
		$db_mysql = new Medoo([
	
			'type' => 'mysql',
			'database' => $mig_prefs['prefs_database_name'],
			'host' => $mig_prefs['prefs_database_host'],
			'username' => $mig_prefs['prefs_database_username'],
			'password' => $mig_prefs['prefs_database_psw'],
		 
			'charset' => 'utf8',
			'port' => $mig_prefs['prefs_database_port'],
		 
			'prefix' => $mig_prefs['prefs_database_prefix']
		]);
		
		$conn = true;
		
	} catch (Exception $e) {
    $conn = false;
    echo '<div class="alert alert-danger">Database Connection failed<hr>';
    print_r($e);
    echo '</div>';
	}  
	
	if($conn === true) {
		echo '<div class="alert alert-success">Database Connection<hr>';
		print_r($db_mysql->info());
		echo '</div>';
		
		echo '<form action="?tn=moduls&sub=migrate.mod&a=start" method="POST">';
		echo '<input type="submit" name="generate_mysql_tables" value="Add Basic MySQL Tables to your Database" class="btn btn-success">';
		echo '<input type="hidden" name="csrf_token" value="'.$_SESSION['token'].'">';
		echo '</form><hr>';
				
		if(isset($_POST['generate_mysql_tables'])) {
			include 'add.mysql_tables.php';
		}
		

		echo '<fieldset>';
		echo '<legend>maybe you have Custom columns in your sqlite files</legend>';
		include 'add.custom_fields.php';
		echo '</fieldset>';
		
		
	}

	
}
echo '</div>';
echo '</div>';

if($_POST['generate_config_file'] == 'generate') {
	
 	$config_db_content = "<?php\n";
  $config_db_content .= "$"."database_host = "."\"".$mig_prefs['prefs_database_host']."\";\n";
  $config_db_content .= "$"."database_user = "."\"".$mig_prefs['prefs_database_username']."\";\n";
  $config_db_content .= "$"."database_psw = "."\"".$mig_prefs['prefs_database_psw']."\";\n";
  $config_db_content .= "$"."database_name = "."\"".$mig_prefs['prefs_database_name']."\";\n";
  $config_db_content .= "$"."database_port = "."\"".$mig_prefs['prefs_database_port']."\";\n";
  $config_db_content .= "define("."\""."DB_PREFIX"."\"".", "."\"".$mig_prefs['prefs_database_prefix']."\");\n";
  $config_db_content .= "?>";
	
	$config_db_file = "../config_database.php";
	file_put_contents($config_db_file, $config_db_content);
	
}





/* import user from sqlite */

echo '<fieldset>';
echo '<legend>migrate SQLite to MySQL (Pages, Posts, User, Preferences)</legend>';

if(is_file('../content/SQLite/user.sqlite3')) {
	include 'import.user.php';	
}

if(is_file('../content/SQLite/content.sqlite3')) {
	include 'import.pages.php';	
}

if(is_file('../content/SQLite/posts.sqlite3')) {
	include 'import.posts.php';	
}

echo '</fieldset>';



/* addons */

echo '<fieldset>';
echo '<legend>import addon files in MySQL or content.sqlite3</legend>';

if(is_file('../content/SQLite/publisher.sqlite3')) {
	include 'import.publisher.php';	
}

echo '</fieldset>';




?>