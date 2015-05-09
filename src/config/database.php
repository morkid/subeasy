<?php
define('DB_HOST','0.0.0.0');
define('DB_NAME','subeasy');
define('DB_USER','root');
define('DB_PASS','');
define('DB_PORT','3333');

$DB_HOST = !empty(DB_PORT) ? DB_HOST.":".DB_PORT : DB_HOST;
$conn_id = mysql_connect($DB_HOST,DB_USER,DB_PASS)or die(mysql_error());
mysql_select_db(DB_NAME,$conn_id)or die(mysql_error());