<?php
if(isset($_POST['collection_movie']) && isset($_FILES['srt'])){
	$file = (object)$_FILES['srt'];
	
	if(strtolower(substr($file->name,-4)) == '.srt')
	{
		$query = array("START TRANSACTION");
		$context = file_get_contents($file->tmp_name);
		$context = str_replace("\r\n","\n",$context);
		$context = explode("\n\n",$context);
		$filename = mysql_real_escape_string($file->name);
		if($context){
			$query[] = "INSERT INTO se_collection (collection_movie,collection_language,collection_filename) 
						VALUES ('{$_POST['collection_movie']}','{$_POST['collection_language']}','{$filename}')";
		}
		$min_length = 0;
		$max_length = 0;
		foreach($context as $n => $text) {
			$text_data = explode("\n",$text);
			$sub_index = (int)$text_data[0];
			if(!$text_data || !$sub_index)
				continue;
			
			$text_time = preg_replace("/[^\w\d\:\,\-]/","",$text_data[1]);
			$text_time = explode("--",$text_time);
			
			$sub_start = $text_time[0];
			$sub_start = time_to_ms($sub_start);

			$sub_end = $text_time[1];
			$sub_end = time_to_ms($sub_end);

			$sub_color = FALSE;
			if(preg_match('/color\="([^"]+)"/',$text_data[2],$color))
				$sub_color = mysql_real_escape_string($color[1]);

			$sub_text = array();
			foreach($text_data as $a => $b) {
				if($a < 2)continue;
				$sub_text[] = $b;
			}
			$sub_text = implode("\n",$sub_text);
			$sub_text = mysql_real_escape_string(strip_tags($sub_text));

			$query[] = "INSERT INTO se_subtitle 
			(subtitle_index,subtitle_color,subtitle_text,subtitle_start,subtitle_end,collection_id) VALUES
			('{$sub_index}','{$sub_color}','{$sub_text}','{$sub_start}','{$sub_end}',(SELECT MAX(collection_id) FROM se_collection))";

			if($n < 1)
				$min_length = (int)$sub_start;
			$max_length = (int)$sub_end;
		}
		$query[] = "COMMIT";
		if(count($query) > 3){
			foreach($query as $q):mysql_query($q)or die(mysql_error());endforeach;
			$max = mysql_query("SELECT MAX(collection_id) FROM se_collection");
			$id = mysql_fetch_row($max);
			$id = $id[0];
			mysql_query("UPDATE se_collection SET collection_length = {$max_length}-{$min_length} WHERE collection_id = '{$id}'");

			$info = '<div class="alert alert-warning">'.
						$_POST['collection_movie'].' subtitle has been saved. '.
						'<a href="?src=create&action=manual&step=2&id='.$id.'">Start Edit</a>'.
					'</div>';
			header("location:?src=create&action=import&success=".urlencode($info));
			exit;
		}
	}
	else
	{
		$info = '<div class="alert alert-warning">We are sorry, subtitle file does not allowed!</div>';
		header("location:?src=create&action=import&success=".urlencode($info));
		exit;
	}

}
?>
<form class="form-horizontal" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend><i class="glyphicon glyphicon-hdd"></i> Import .srt file</legend>
		<?php if(isset($_GET['success']))echo urldecode($_GET['success']);?>
		<div class="form-group">
			<label class="control-label col-md-2">Movie Title :</label>
			<div class="col-md-4"><input type="text" class="form-control" required name="collection_movie"></div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-2">Language :</label>
			<div class="col-md-4"><input type="text" class="form-control" required name="collection_language"></div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-2">.srt file :</label>
			<div class="col-md-4"><input type="file" class="form-control" required name="srt"></div>
		</div>
		<div class="form-group">
			<div class="col-md-4 col-md-offset-2">
				<button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-hdd"></i> Save</button>
			</div>
		</div>
	</fieldset>
</form>