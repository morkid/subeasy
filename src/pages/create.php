<?php
$action = isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : FALSE;

if($action)
	require_once PAGE_DIR . $action."_action.php";