<?php
/* ---- Set page title and description ---- */
$page_title = "Previous Presentations";
$page_description = "";

/* ---- include the header file ---- */
include("header.php");

/* ---- include the database connection script ---- */
include("database.php");


?>
<div class="pageContainer">

  <!--<div class="pageContent" style="width:100%; height:25%; overflow:scroll;"> -->
  <div class="pageContent" style="width:100%; height:800px; overflow:scroll; overflow-x: hidden;">
  
    <span id="body_main_header">PREVIOUS PRESENTATIONS</span> <br />
    <hr />
	<!-- should not put anything before this -->
	<?php 
		/* PULL OUT THE DATA FOR ALL THE PRESENTATIONS UPLOADED IN THE DATABASE */
		$query = "select * from previous_presentations order by presented_on DESC";
		//echo "sql=$query <br />";
		
		$result = mysqli_query(connect_to_db(),$query) or die (mysqli_error());
		$num_rows = mysqli_num_rows($result);
	?>
	<table border="1" cellpadding="3" class="sortable">
		<tr>
			<th>S.No</th>
			<th>Paper Name</th>
			<th>Presented By</th>
			<th>Presented On</th>
			<th>Type</th>
			<th>Presentation</th>
		</tr>
	<?php
	$i=0;
	while ($row = mysqli_fetch_assoc($result)){
		$presentation_title = $row['presentation_title'];
		$presented_by = htmlentities($row['presented_by']);
		$presented_on_date = $row['presented_on'];
		$presentation_type = $row['presentation_type'];
		
		//get the date.
		$date_array = preg_split("/\-/",$presented_on_date);
		$date = preg_split("//",$date_array[2]);
		// $date = 0 4 t h
		// $date = 2 1 s t
		$sortable_custom_key = "$date_array[0]$date_array[1]$date[1]$date[2]";
		if($date[1]!=0){
			$final_date = "$date[1]$date[2]$date[3]$date[4]";
		}else{
			$final_date = "$date[2]$date[3]$date[4]";
		}
		switch( $date_array[1] ){
			case '01': $month="Janurary"; break;
			case '02': $month="February"; break;
			case '03': $month="March"; break;
			case '04': $month="Apri"; break;
			case '05': $month="May"; break;
			case '06': $month="June"; break;
			case '07': $month="July"; break;
			case '08': $month="August"; break;
			case '09': $month="September"; break;
			case '10': $month="October"; break;
			case '11': $month="November"; break;
			case '12': $month="December"; break;
			default:
			throw new Exception("Invalid Month Entry");
	}
		$presented_on = "$final_date $month $date_array[0]";
		
		// get the file names.
		$file_name = $row['presentation_name'];
		$filename_array = preg_split("/\|/",$file_name);
		$count = count(preg_split("/\|/",$file_name));
		for($j=0; $j<$count; $j++){
			$filepath[$j] ="docs/presentations/".$filename_array[$j];
		}
		$i++;
	?>
		<tr>
			<td><?php echo "$i." ?></td>
			<td><?php echo $presentation_title ?></td>
			<td><?php echo $presented_by ?></td>
			<td  sorttable_customkey="<?php echo $sortable_custom_key ?>"><?php echo $presented_on ?></td>
			<td><?php echo $presentation_type ?></td>
			<td>
				<?php
				if(strlen($file_name) != 0){
					for($k=0; $k<$count; $k++){?>
						<a href="<?php echo $filepath[$k] ?>" target="_blank"> 
						<?php
								// show text icon if not ppt
								if (preg_match ("/ppt|pptx/i", $filepath[$k])) { ?>
									<img src="images/pptx.png" width="40" height="40" border="0" alt="" />
								<?php
								} elseif (preg_match ("/pdf/i", $filepath[$k])) { ?>
									<img src="images/PDF.png" width="40" height="40" border="0" alt="" />
								<?php
								} elseif (preg_match ("/mov|avi|mp4/i", $filepath[$k])) { ?>
									<img src="images/mov.png" width="40" height="40" border="0" alt="" />
								<?php
								}  else { ?>
									<img src="images/txt.png" width="40" height="40" border="0" alt="" />
								<?php } ?>

						</a> 
					<?php
					}
				}?>
			</td>
		</tr>
		
	<?php } ?>
	</table>

	<!-- should not put anything after this div -->
<?php
/* ---- include the footer file ---- */
include("footer.php")

?>
