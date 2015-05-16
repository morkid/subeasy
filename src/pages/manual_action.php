<?php 
$id = isset($_GET['id']) && $_GET['id'] > 0 ? $_GET['id'] : FALSE;
$sub = isset($_GET['sub']) && $_GET['sub'] > 0 ? $_GET['sub'] : FALSE;
$step = isset($_GET['step']) ? $_GET['step'] : 1;
if(isset($_GET['remove'])) {
	mysql_query("DELETE FROM se_collection WHERE collection_id = '{$_GET['remove']}'");
	mysql_query("DELETE FROM se_subtitle WHERE collection_id = '{$_GET['remove']}'");
	header("location:?src=collections");
	exit;
}
if(isset($_GET['sub_remove'])) {
	mysql_query("DELETE FROM se_subtitle WHERE subtitle_id = '{$_GET['sub_remove']}'");
	header("location:?src=create&action=manual&step=2&id=".$id);
	exit;
}
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4><i class="glyphicon glyphicon-pencil"></i> Write subtitle manually <small><em id="movie"></em></small></h4>
	</div>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li<?php if($step==1)echo ' class="active"'?>>
				<a href="?src=create&amp;action=manual&amp;step=1<?php if($id)echo '&amp;id='.$id?>">Movie Info</a>
			</li>
			<li<?php if($step==2)echo ' class="active"'?>>
				<a href="?src=create&amp;action=manual&amp;step=2<?php if($id)echo '&amp;id='.$id?>">Subtitle content</a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active">
				<p></p>
<?php 
	if(isset($_GET['save'])){
		setcookie("collections",NULL);
		$_COOKIE['collections'] = NULL;
		header("location:?src=collections");
		exit;
	}

	if(isset($_POST['collection_movie'])) {
		$data = $_POST;
		if($id){
			$fields = array();
			foreach($data as $a => $b){
				$fields[] = $a ."='".mysql_real_escape_string($b)."'";
			}
			mysql_query("UPDATE se_collection SET ".implode(",",$fields)." WHERE collection_id = '{$id}'");
			header("location:?src=create&action=manual&step=2&id=".$id);
			exit;
		}
		else
		{
			$data['collection_filename'] = $_POST['collection_movie'].".srt";
			setcookie("collections",json_encode($data));
			header("location:?src=create&action=manual&step=2");
			exit;
		}
	}

	$cookies = isset($_COOKIE['collections']) && $_COOKIE['collections'] ? json_decode($_COOKIE['collections'],1) : FALSE;
	
	$query = mysql_query("SELECT * FROM se_collection WHERE collection_id = '{$id}' LIMIT 1");
	$query2 = mysql_query("SELECT * FROM se_subtitle WHERE subtitle_id = '{$sub}' LIMIT 1");
	$collections = mysql_fetch_assoc($query);
	if($cookies)$collections = $cookies;
	$subtitle =  mysql_fetch_assoc($query2);

	if(isset($_POST['subtitle_text'])) {
		if(!$id) {
			mysql_query("INSERT INTO se_collection (collection_movie,collection_language,collection_filename) 
						VALUES ('{$cookies['collection_movie']}','{$cookies['collection_language']}','{$cookies['collection_filename']}')");
			$id = mysql_insert_id($conn_id);
			setcookie("collections",NULL);
			$_COOKIE['collections'] = NULL;
		}
		
		if($_POST['new'] < 1){
			mysql_query("DELETE FROM se_subtitle WHERE subtitle_id = '{$sub}'");
		}

		mysql_query("INSERT INTO se_subtitle 
		(subtitle_index,subtitle_color,subtitle_text,subtitle_start,subtitle_end,collection_id) VALUES
		(
			'{$_POST['subtitle_index']}',
			'{$_POST['subtitle_color']}',
			'{$_POST['subtitle_text']}',
			'{$_POST['subtitle_start']}',
			'{$_POST['subtitle_end']}',
			'{$id}'
		)");
		if(isset($subtitle['subtitle_start']) && $_POST['parallel'] == 1 && ($_POST['subtitle_start'] != $subtitle['subtitle_start'])) {
			$returns = ($subtitle['subtitle_start']-$_POST['subtitle_start']);
			mysql_query("UPDATE se_subtitle 
				SET 
					subtitle_start = subtitle_start - {$returns},
					subtitle_end = subtitle_end - {$returns} 
				WHERE 
					collection_id = '{$id}' AND subtitle_index > {$_POST['subtitle_index']}");
		}
		header("location:?src=create&action=manual&step=2&id=".$id);
		exit;
	}

	switch($step):
		case 1:
?>
<form class="form-horizontal" method="post" enctype="multipart/form-data" data-ajax="false" id="form-movie">
	<input type="hidden" name="collection_filename">
	<div class="form-group">
		<label class="control-label col-md-2">Movie Title :</label>
		<div class="col-md-4"><input type="text" class="form-control" required name="collection_movie"></div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-2">Language :</label>
		<div class="col-md-4"><input type="text" class="form-control" required name="collection_language"></div>
	</div>
	<div class="form-group">
		<div class="col-md-10 col-md-offset-2">
			<button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-chevron-right"></i> Save and Continue</button>
			<?php if($id):?>
				<a href="?src=create&amp;action=manual&amp;step=1&amp;remove=<?php echo $id?>" class="remove btn btn-danger">
					<i class="glyphicon glyphicon-trash"></i> Remove This movie subtitle
				</a>
			<?php endif;?>
		</div>
	</div>
</form>
<?php 
	break;
	case 2:
	if(!$cookies && !$id){
		header('location:?src=create&action=manual&step=1');
		exit;
	}
	$index = 1;
	if($id){
		$subtitle_query = mysql_query("SELECT MAX(subtitle_index) subs FROM se_subtitle WHERE collection_id = '{$id}'");
		$index = mysql_fetch_row($subtitle_query);
		$index = (int)$index[0] + 1;
	}
?>
<a href="?src=create&amp;action=manual&amp;step=1<?php if($id)echo '&amp;id='.$id?>" class="btn btn-primary">
	<i class="glyphicon glyphicon-repeat"></i> Back
</a>
<a href="?src=collections" class="btn btn-info">
	<i class="glyphicon glyphicon-ok"></i> Back to collections
</a>
<hr>
<form class="form-horizontal" method="post" enctype="multipart/form-data" data-ajax="false" id="form-subtitle">
	<div class="form-group">
		<label class="control-label col-md-2">Section Index :</label>
		<div class="col-md-2"><input type="text" class="form-control" required name="subtitle_index" value="<?php echo $index?>"></div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-2">Subtitle :</label>
		<div class="col-md-6"><textarea class="form-control" required name="subtitle_text" rows="5"></textarea></div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-2">Color :</label>
		<div class="col-md-2"><input type="color" class="form-control" value="#ffffff" required name="subtitle_color"></div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-2">Start :</label>
		<div class="col-md-10">
			MS : <input type="text" name="subtitle_start" required value="0" class="numeric ms" data-ms="start">
			TIME : <input type="text" readonly value="0" style="border:0">
			<div class="time-slider"></div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-2">End :</label>
		<div class="col-md-10">
			MS : <input type="text" name="subtitle_end" required value="0" class="numeric ms" data-ms="end">
			TIME : <input type="text" readonly value="0" style="border:0">
			<div class="time-slider"></div>
		</div>
	</div>
	<div class="form-group <?php if(!$id)echo 'hide'?>">
		<label class="control-label col-md-2">Move all next sections :</label>
		<div class="col-md-2">
			<div class="radio-inline">
				<input type="radio" value="1" name="parallel"> Yes
      		</div>
			<div class="radio-inline">
          		<input type="radio" value="0" checked name="parallel"> No
      		</div>
      	</div>
	</div>
	<div class="form-group <?php if(!$sub)echo 'hide'?>">
		<label class="control-label col-md-2">Save as new :</label>
		<div class="col-md-2">
			<div class="radio-inline">
          		<input type="radio" value="1" name="new"> Yes
      		</div>
			<div class="radio-inline">
          		<input type="radio" value="0" checked name="new"> No
      		</div>
      	</div>
	</div>
	<div class="form-group">
		<div class="col-md-10 col-md-offset-2">
			<button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-ok"></i> <?php echo $sub ? 'Update' : 'Add'?></button>
			<a href="?src=create&amp;action=manual&amp;step=2<?php if($id)echo '&amp;id='.$id?>" class="btn btn-primary">
				<i class="glyphicon glyphicon-repeat"></i> Cancel
			</a>
		</div>
	</div>
</form>
<?php
	$subtitle_query = mysql_query("SELECT * FROM se_subtitle WHERE collection_id = '{$id}' 
		ORDER BY ABS(subtitle_index) ASC,subtitle_start ASC");
	if(mysql_num_rows($subtitle_query) > 0):
?>
<hr>
<table class="table table-data-dynamic table-condensed" data-state-save="true">
	<thead>
		<tr>
			<th>#</th>
			<th>Color</th>
			<th>Text</th>
			<th>Start</th>
			<th>End</th>
			<th>Options</th>
		</tr>
	</thead>
	<tbody>
		<?php
			while($o = mysql_fetch_object($subtitle_query)):
		?>
		<tr>
			<td><?php echo $o->subtitle_index?></td>
			<td><strong style="color:<?php echo $o->subtitle_color?>"><?php echo strtoupper($o->subtitle_color)?></strong></td>
			<td><?php echo $o->subtitle_text?></td>
			<td><?php echo ms_to_time($o->subtitle_start)?></td>
			<td><?php echo ms_to_time($o->subtitle_end)?></td>
			<td>
				<a href="?src=create&amp;action=manual&amp;step=2<?php echo '&amp;id='.$id.'&amp;sub='.$o->subtitle_id?>" 
				class="btn btn-xs btn-primary">
					<i class="glyphicon glyphicon-pencil"></i>
				</a>
				<a href="?src=create&amp;action=manual&amp;step=2<?php echo '&amp;id='.$id.'&amp;sub_remove='.$o->subtitle_id?>" 
				class="btn btn-xs btn-danger sub_remove">
					<i class="glyphicon glyphicon-remove"></i>
				</a>
			</td>
		</tr>
		<?php endwhile;?>
	</tbody>
</table>
<?php 
		endif;
		break;
	endswitch;
?>
			</div>
		</div>
	</div>
</div>
<script>
	function lpad(str,len,padd) {
		str = str.toString();
		str = str.substring(0,len);
		var l = str.length;
		var text = '';
		if(l < len) {
			for(var e = 0; e < len-l; e++) {
				str = padd+str;
			}
		}
		return str;
	}

	function ms_to_time(milliseconds) {
		milliseconds = parseFloat(milliseconds);
		if(milliseconds < 1)
			return 0;
		var mil = milliseconds;
		var sec = Math.floor(mil / 1000);
		var min = Math.floor(sec / 60);
		var hwr = Math.floor(min / 60);
		var mil = mil % 1000;
		var sec = sec % 60;
		var min = min % 60;
		hwr = isNaN(hwr) ? 0 : hwr;
		min = isNaN(min) ? 0 : min;
		sec = isNaN(sec) ? 0 : sec;
		mil = isNaN(mil) ? 0 : mil;
		var time = lpad(hwr,2,0)+':'+lpad(min,2,0)+':'+lpad(sec,2,0)+','+lpad(mil,3,0);
		return time;
	}

	function time_to_ms(time) {
		var time = time.replace(/[^\d]/g,":",time.toString());
		var time = time.split(":");

		var ml = 1000;
		var sc = 60;
		var mn = 60;

		var hwr = parseInt(time[0]) * (mn*(sc*ml));
		var min = parseInt(time[1]) * (sc*ml);
		var sec = parseInt(time[2]) * (ml);
		var mil = parseInt(time[3]);
		var time_string = hwr+min+sec+mil;
		if(!time_string)
			return 0;
		return parseInt(time_string);
	}

	$(document).on("click",".remove",function(e){
		if(window.confirm("Remove movie subtitle?"))
			return true;
		return false;
	});
	$(document).on("click",".sub_remove",function(e){
		if(window.confirm("Remove this section?"))
			return true;
		return false;
	});
	var lastDiff = false;
	$(document).on("change",'[name="new"]',function(e){
		if($(this).val() == "1") {
			if(!lastDiff)
				lastDiff = $(".ms").eq(0).data('diff');
			$(".ms").attr("data-diff",0).data("diff",0);
		}
		else{
			$(".ms").attr("data-diff",lastDiff).data("diff",lastDiff);
		}
	});

	var currentAction = "";
	$.fn.parallel = function(slider,preview)
	{
		var otherMs = $(".ms").not(this);
		var currentVal = parseInt(this.val());
			currentVal = isNaN(currentVal) ? 0 : currentVal; 
		var otherVal = parseInt(otherMs.val());
			otherVal = isNaN(otherVal) ? 0 : otherVal;

		if(currentAction == "") {
			currentAction = this.data('ms');
			if(this.data('diff') > 0) {
				var curVal = currentVal + this.data("diff");
				if(currentAction == "end")
					curVal = currentVal - this.data("diff");
				otherMs.val(curVal).trigger("keyup");
			}
		}
		slider.slider( "value", currentVal);
		preview.val( ms_to_time(currentVal) );
		currentAction = "";
	}

	$(document).ready(function(){
		var collections = <?php echo json_encode($collections)?>;
		var subtitle = <?php echo json_encode($subtitle)?>;
		var title = '';
		if(!subtitle.subtitle_color)
			subtitle.subtitle_color = '#ffffff';
		
		if(collections.collection_movie) {
			title = '<a href="?src=download&theme=false&id='+collections.collection_id+'" target="_blank">'+
					'<i class="glyphicon glyphicon-download"></i> '+
					collections.collection_movie+
					'</a>';
		}
		$("#movie").html(title);
		$("#form-movie").simpleForm({data:collections});
		$("#form-subtitle").simpleForm({data:subtitle});
		var startVal = subtitle.subtitle_start || 0;
		var endVal = subtitle.subtitle_end || 0;
		var diffVal = endVal - startVal;
		$(".ms").attr('data-diff',diffVal).data('diff',diffVal);

		$(".time-slider").each(function(a,b){
			var preview = $(b).prev();
			var input = preview.prev();
			var data = input.data();
			var min = data.min || 0;
			var val = time_to_ms(input.val());
			preview.val( ms_to_time(input.val()) );
			var slider = $(b).slider({
				range:data.range || "min",
				min: 0,
				max: data.max || 18000000,
				slide: function( event, ui ) {
					preview.val( ms_to_time(ui.value) );
					input.val( ui.value );
					if(currentAction == ""){
						input.trigger("keyup");
					}
				},
				value: parseInt(input.val())
			});
			

			$(document).on("keyup",".ms:eq("+a+")",function(e){
				$(this).parallel(slider,preview);
			});
			$(document).on("change",".ms:eq("+a+")",function(e){
				$(this).parallel(slider,preview);
			});
		});
	});
</script>