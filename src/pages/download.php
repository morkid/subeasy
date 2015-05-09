<?php
if(isset($_GET['id'])):
	$id = $_GET['id'];
	$query = mysql_query("SELECT * FROM se_collection WHERE collection_id = '{$id}' LIMIT 1");
	$collections = mysql_fetch_object($query)or die(mysql_error());
	header("Content-type: text/plain");
	header("Content-Disposition: attachment; filename={$collections->collection_filename}.srt");
	header("Pragma: no-cache");
	header("Expires: 0");
	$query2 = mysql_query("SELECT * FROM se_subtitle 
		WHERE collection_id = '{$id}' 
		ORDER BY ABS(subtitle_index) ASC, subtitle_start ASC")or die(mysql_error());
	
	$subtitles = array();

	while($o = mysql_fetch_object($query2)):
		$text = array();
		$text[] = $o->subtitle_index;
		$text[] = ms_to_time($o->subtitle_start)." --> ".ms_to_time($o->subtitle_end);
		$template = '%s';
		if($o->subtitle_color)
			$template = '<font color="'.$o->subtitle_color.'">%s</font>';
		$text[] = sprintf($template, $o->subtitle_text);
		$subtitles[] = implode("\n",$text);
	endwhile;

	echo implode("\n\n",$subtitles);
	exit;
endif;