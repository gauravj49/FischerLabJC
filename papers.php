<?php
/* ---- Set page title and description ---- */
$page_title = "papers of interest";
$page_description = "A journal club is a group of individuals who meet regularly to critically evaluate recent articles in scientific literature.";

/* ---- include the header file ---- */
include("header.php");

/* ---- include the database connection script ---- */
include("database.php");

?>

<script type="text/javascript">
  //<![CDATA[

	   window.onload = function() {
	     var table = document.getElementById('expandable_table');
	     if (table) {
	       var count=0;
	       var trs = table.getElementsByTagName('tr');
	       for(var i = 0; i < trs.length; i++) {
		 var a = trs[i].getElementsByTagName('td')[0].getElementsByTagName('a')[0];
		 a.onclick = function() {
		   //make all the span.display = none to collapse all the table rows and then just expand the one that is clicked.
		   for(var i = 0; i < trs.length; i++) {
		     //  trs[i].getElementsByTagName('td')[0].getElementsByTagName('a')[0].parentNode.getElementsByTagName('span')[0].style.display = "none";
		     trs[i].getElementsByTagName('td')[0].getElementsByTagName('a')[0].parentNode.getElementsByTagName('span')[0].className = "displayNoneWOpadding";
		   }
		   var span = this.parentNode.getElementsByTagName('span')[0];
		   //span.style.display = span.style.display == 'none' ? "block" : 'none';
		   span.className = span.className == "displayNoneWOpadding" ? "displayBlockWpadding" : "displayNoneWOpadding";
		 };
	       }
	     }
	   };
//]]>

</script>


<div class="pageContainer">
  <div class="pageContent" style="width:100%; height:100%;">
    <span id="body_main_header">Papers of interest</span> <br />
    <hr />
        <table id="expandable_table">
	   <tbody>
	       <?php
  /**----------------------------------------
   For Pagination
   ----------------------------------------**/
$no_of_entries_on_a_page = 15;

/* How many adjacent pages should be shown on each side */
$adjacents = 1;

/**----------------------------------------------------------------
 Count the number of entries and get the number of pages needed. 
 ----------------------------------------------------------------**/
$sql = "SELECT COUNT(paper_title) FROM current_entries"; 
$rs_result = mysqli_query(connect_to_db(),$sql); 
$row = mysqli_fetch_row($rs_result); 
$total_records = $row[0]; 
$total_pages = ceil($total_records / $no_of_entries_on_a_page); 


$targetpage = "papers.php";
if (isset($_GET["page"])) { 
  $page  = $_GET["page"]; 
 }else{ 
  $page=0; 
 }

if($page){ 
  $start = ($page-1) *  $no_of_entries_on_a_page;//first item to display on this page
 }else{
  $start = 0;	//if no page var is given, set start to 0
 }

/**------------------------------------------------
 Display the results from the database
 ------------------------------------------------**/


/* Setup page vars for display. */
if ($page == 0) $page = 1; //if no page var is given, default to 1.
$prev = $page - 1; //previous page is page - 1
$next = $page + 1; //next page is page + 1
$lastpage = ceil($total_records/$no_of_entries_on_a_page); //lastpage is = total pages / items per page, rounded up.
$lpm1 = $lastpage - 1; //last page minus 1

$q = "SELECT * FROM current_entries ORDER BY id DESC LIMIT $start, $no_of_entries_on_a_page";
$r = mysqli_query(connect_to_db(),$q);
$row_count= $start;
if(mysqli_num_rows($r)>0): //table is non-empty
  while($row = mysqli_fetch_assoc($r)):
$db_link    = $row['link'];
$abstract   = htmlspecialchars($row['Summary']);
$file_name  = $row['paper_name'];
$paper_title = htmlentities($row['paper_title']);
$submitted_by   = $row['submitted_by'];

$filename_array = preg_split("/\|/",$file_name);
$count = count(preg_split("/\|/",$file_name));
for($i=0; $i<$count; $i++){
	$filepath[$i] ="docs/papers/".$filename_array[$i];
}

/* ------------------------------------------------------------
 removing the http:// from the link and attaching it again.
 Don`t know why i have to do this ... but without doing this 
 it adds http://my5c.umassmed.edu/ before the actual link.
 ----------------------------------------------------------*/

$db_link = preg_replace("/http:\/\//", '',$db_link);
$db_link = preg_replace("/https:\/\//", '',$db_link);
$db_link = preg_replace("/http\/\//", '',$db_link);
$db_link = "http://".$db_link;
$db_link = htmlentities($db_link);

$row_count++;
?>
        <tr>
		<td> 
		    <?php echo $row_count;?>) 
		      <a href="#<?php echo $paper_title ?>"  class="noDocLinks"> 
			 <?php
			     echo $paper_title;
			 ?>
		      </a>
		        <!-- <span style="display:none;" > -->
				<span class="displayNoneWOpadding">
			     <a href="<?php echo $db_link ?>" target="_blank" style="text-decoration: none;color: black;">
			     <b>Abstract: </b> <img src="images/external-link-icon.png" alt="external link" style="border:none;"/> </a><?php echo $abstract?><br />
				 <b>Submitted by: </b> <?php echo $submitted_by?><br />
				 <!-- if there is a file attached to it. Then show the download option. -->
				<?php
				if(strlen($file_name) != 0){?>
				<b>Attachments: </b> 
				<?php 
					for($j=0; $j<$count; $j++){?>
							<br />
							<a href="<?php echo $filepath[$j] ?>" target="_blank" style="text-decoration: none;color: black;">
							<img src="images/PDF.png" alt="external link" style="border:none; width:30px; height:30px;"/> ( <?php echo $filename_array[$j] ?> )</a>
							<?php
					}
				}?>

				</span>
				
				<span class="dis-playNoneWOpadding"> </span>
				<!--  <span style="display:none;"> </span> -->
		    </td>
		</tr>
		<?php
		    endwhile;
		    endif;
		?>
	   </tbody>
        </table>
        
 <?php 
     /* 
 Now we apply our rules and draw the pagination object. 
 We're actually saving the code to a variable in case we want to draw it more than once.
*/
$pagination = "";
if($lastpage > 1){	
  $pagination .= "<div class=\"pagination\">";
  //previous button
  if ($page > 1){ 
    $pagination.= "<a href=\"$targetpage?page=$prev\">&laquo; Previous</a>";
  }else{
    $pagination.= "<span class=\"disabled\">&laquo; Previous</span>";	
  }
  //pages	
  if ($lastpage < 7 + ($adjacents * 2)){	//not enough pages to bother breaking it up
    for ($counter = 1; $counter <= $lastpage; $counter++){
      if ($counter == $page){
	$pagination.= "<span class=\"current\">$counter</span>";
      }else{
	$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
      }
    }
  }elseif($lastpage > 5 + ($adjacents * 2)){	//enough pages to hide some
    //close to beginning; only hide later pages
    if($page < 1 + ($adjacents * 2)){
      for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
	if ($counter == $page){
	  $pagination.= "<span class=\"current\">$counter</span>";
	}else{
	  $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
	}
      }
      $pagination.= "...";
      $pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
      $pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
    }
    //in middle; hide some front and some back
    elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
      $pagination.= "<a href=\"$targetpage?page=1\">1</a>";
      $pagination.= "<a href=\"$targetpage?page=2\">2</a>";
      $pagination.= "...";
      for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++){
	if ($counter == $page){
	  $pagination.= "<span class=\"current\">$counter</span>";
	}else{
	  $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
	}
      }
      $pagination.= "...";
      $pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
      $pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
    }
    //close to end; only hide early pages
    else{
      $pagination.= "<a href=\"$targetpage?page=1\">1</a>";
      $pagination.= "<a href=\"$targetpage?page=2\">2</a>";
      $pagination.= "...";
      for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++){
	if ($counter == $page){
	  $pagination.= "<span class=\"current\">$counter</span>";
	}else{
	  $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
	}
      }
    }
  }
  
  //next button
  if ($page < $counter - 1){ 
    $pagination.= "<a href=\"$targetpage?page=$next\">Next &raquo;</a>";
    $pagination.= "</div>";		
  }else{
    $pagination.= "<span class=\"disabled\">Next &raquo;</span>";
    $pagination.= "</div>";		
  }
 }   
 
 echo $pagination;
 ?>
<br class="c1" />

<!-- should not put anything after this div -->


<?php
/* ---- include the footer file ---- */
include("footer.php")

?>
