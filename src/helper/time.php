<?php

function ms_to_time($milliseconds = 0) {
	if($milliseconds < 1)
		return '~';
	$mil = $milliseconds;
	$sec = floor($mil / 1000);
	$min = floor($sec / 60);
	$hwr = floor($min / 60);
	$mil = $mil % 1000;
	$sec = $sec % 60;
	$min = $min % 60;
	$time = sprintf("%02u:%02u:%02u,%03u",$hwr,$min,$sec,$mil);
	return $time;
}

function time_to_ms($time = '00:00:00,000') {
	$time = preg_replace("/[^\d]/",":",$time);
	$time = explode(":",$time);

	$ml = 1000;
	$sc = 60;
	$mn = 60;

	$hwr = $time[0] * ($mn*($sc*$ml));
	$min = $time[1] * ($sc*$ml);
	$sec = $time[2] * ($ml);
	$mil = $time[3];
	$time_string = $hwr+$min+$sec+$mil;
	if(!$time_string)
		return 0;
	return $time_string;
}