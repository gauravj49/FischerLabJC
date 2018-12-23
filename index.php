<?php
/* ---- Set page title and description ---- */
$page_title = "Journal Club Home";
$page_description = "A journal club is a group of individuals who meet regularly to critically evaluate recent articles in scientific literature.";

/* ---- include the header file ---- */
include("header.php");

/* ---- include the database connection script ---- */
include("database.php");
?>

<!-- This Week Section
// http://1plusdesign.com/articles/add-countdown-timer-website/
// disable this to remove the counter
 -->
<script src="js/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.countdown.pack.js"></script>
<script type="text/javascript">
	$(function () {
		$('#countdown').countdown({until:$.countdown.UTCDate(-4, 2012, 9 - 1, 7, 12, 0), format: 'DHMS', layout: 
		  '<div id="timer" />'+
			  '<div id="timer_days" class="timer_numbers">{dnn}</div>'+
			  '<div id="timer_hours" class="timer_numbers">{hnn}</div>'+ 
			  '<div id="timer_mins" class="timer_numbers">{mnn}</div>'+
			  '<div id="timer_seconds" class="timer_numbers">{snn}</div>'+
			'<div id="timer_labels">'+
				'<div id="timer_days_label" class="timer_labels">days</div>'+
				'<div id="timer_hours_label" class="timer_labels">hours</div>'+
				'<div id="timer_mins_label" class="timer_labels">mins</div>'+
				'<div id="timer_seconds_label" class="timer_labels">secs</div>'+
			'</div>'+							
		'</div>'
								  
		});
	});
</script>
<!-- End of countdown Section -->

<div class="pageContainer">

  <div class="pageContent" style="width:100%; height:100%;">
  
    <span id="body_main_header">THIS WEEK</span> <br />
    <hr />

<?php

    /* PULL OUT THE DATA FOR THE CURRENT PAPER */
    $q = "SELECT * FROM current_entries where current=1";
    $r = mysqli_query(connect_to_db(),$q);

    if(mysqli_num_rows($r)>0): //table is non-empty
       $row = mysqli_fetch_assoc($r);
       $db_link    = $row['link'];
       $abstract   =  htmlspecialchars($row['Summary']);
	   $submitted_by = $row['submitted_by'];
       
    /* ------------------------------------------------------------
       removing the http:// from the link and attaching it again.
       Don`t know why i have to do this ... but without doing this 
       it adds http://my5c.umassmed.edu/ before the actual link.
       -----------------------------------------------------------*/

    $db_link = preg_replace("/http:\/\//", '',$db_link);
    $db_link = preg_replace("/https:\/\//", '',$db_link);
    $db_link = preg_replace("/http\/\//", '',$db_link);
    $db_link = "http://".$db_link;
	$db_link = htmlentities($db_link);
?>

<!-- This Week Section -->

<table>
	<tr>
		<td class="new_title">
			<a href="<?php echo $db_link ?>" target='_blank'> <font color="#000000"><?php echo  htmlentities($row['paper_title']); ?> </font></a>
        </td>
    </tr>
    
    <tr>
        <td id="presented_by">
           <br />
           <label> <b>Presented by:</b><?php echo " $submitted_by" ; ?></label>
	</td>
    </tr>

    <tr> 
        <td id="abs_desc">
           <br />
           <a href="<?php echo $db_link ?>" target="_blank" style="text-decoration: none;color: black;">
              <b>Abstract:</b><img src="images/external-link-icon.png" alt="external link" style="border:none;"/>
           </a> 
		   
		   <?php echo $abstract?>
		   
		   <!--- Customized abstract. comment the line above and uncomment the line below to use. 
		   Welcome back everyone from the summer break. <br /> <br />This week in Journal club, I will present couple of "Points of View Column" from Nature Methods. "Points of View" is a monthly column published by Nature Methods that deals with the fundamental aspects of visual presentation applicable to anyone who works with visual representation of data.<br />Each month Bang Wong (Creative Director, Broad Institute) focuses on a particular aspect of data presentation or visualization and provide easy-to-apply tips on how to create effective presentations.<br /><br />I will also share some Adobe Illustrator tips and tricks to make you more productive. Although all of these tips and tricks work in the latest version of Illustrator, some go back to previous versions and therefore can be used by all.
		   -->

		</td>
	 </tr>
</table>

<?php
    endif;
?>
<!---
<div id="wrapper">
<div id="countdown"> </div><!--close countdown
</div>
-->
<!-- should not put anything after this div -->
<?php
/* ---- include the footer file ---- */
include("footer.php")

?>
