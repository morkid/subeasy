<table class="table table-condensed table-data-dynamic"
data-disable-info="true"
data-disable-sort="[0,1,2,3,4]"
data-disable-tools="true">
	<thead>
		<tr>
			<th>Movie</th>
			<th>File Name</th>
			<th>Description</th>
			<th>Length</th>
			<th>Date Created</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			setcookie("collections",NULL);
			$_COOKIE['collections'] = NULL;
			$query = mysql_query("SELECT * FROM se_collection ORDER BY collection_created DESC");
			while($o = mysql_fetch_object($query)):
		?>
		<tr>
			<td>
				<a href="?src=create&amp;action=manual&amp;id=<?php echo $o->collection_id?>">
					<b><?php echo $o->collection_movie?></b>
				</a>
			</td>
			<td><?php echo $o->collection_filename?></td>
			<td><?php echo $o->collection_language?></td>
			<td><?php echo ms_to_time($o->collection_length)?></td>
			<td><?php echo date("D, d F Y, h.i A",strtotime($o->collection_created))?></td>
		</tr>
		<?php endwhile;?>
	</tbody>
</table>