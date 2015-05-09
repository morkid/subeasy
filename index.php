<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

define("ROOT_DIR",str_replace("\\","/",pathinfo(__FILE__,PATHINFO_DIRNAME)."/"));
define("CONFIG_DIR",ROOT_DIR."src/config/");
define("PAGE_DIR",ROOT_DIR."src/pages/");
define("HELPER_DIR",ROOT_DIR."src/helper/");

$require_path = isset($require_path) ? $require_path : 'collections';
if(isset($_GET['src']) && !empty($_GET['src'])) {

	if(file_exists(PAGE_DIR . $_GET['src'] . '.php'))
		$require_path = $_GET['src'];
	else
		$require_path = '404';
}
define('CURRENT_FILE',$require_path);
define('CURRENT_PAGE',PAGE_DIR . CURRENT_FILE . '.php');

if(!defined('INSTALL')){
	if(!file_exists(CONFIG_DIR . 'database.php'))
		header('location:install.php');
	require_once HELPER_DIR . 'time.php';
	require_once CONFIG_DIR . 'database.php';
	if(isset($_GET['theme']) && $_GET['theme'] == 'false')
		require_once CURRENT_PAGE;
	else
		require_once PAGE_DIR . 'template.php';
}