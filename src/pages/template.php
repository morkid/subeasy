<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>SUB.EASY &#8226; <?php echo preg_replace("/[^\w\d]/"," ",strtoupper(CURRENT_FILE))?></title>
	<link rel="stylesheet" href="public/css/cerulean/bootstrap.css">
	<link rel="stylesheet" href="public/plugins/jquerydataTables/css/dataTables.bootstrap.css">
	<link rel="stylesheet" href="public/css/jquery-ui.min.css">
	<link rel="stylesheet" href="public/css/style.css">
	<script src="public/js/jquery-1.11.0.min.js"></script>
	<script src="public/js/jquery-ui.min.js"></script>
	<script src="public/js/bootstrap.min.js"></script>
	<script src="public/plugins/jquerydataTables/js/jquery.dataTables.min.js"></script>
	<script src="public/plugins/jquerydataTables/js/dataTables.bootstrap.js"></script>
	<script src="public/js/app.js"></script>
</head>
<body>
	<?php require_once PAGE_DIR . 'basic_navbar.php';?>
	<div class="container">
		<?php require_once CURRENT_PAGE;?>
	</div>
	<div id="footer" class="well">
		<p class="text-center">&copy; Copyright 2015 | Created By Morkid | <a href="">Sub.Easy</a> All rights reserved.</p>
	</div>
</body>
</html>