<?php
/* ---- Set page title and description ---- */
$page_title = "Schedule : Journalclub";
$page_description = "Journal club schedule";

/* ---- include the header file ---- */
include("header.php");

/* ---- include the database connection script ---- */
include("database.php");


?>
<div class="pageContainer">

  <div class="pageContent" style="width:100%;">
  
    <span id="body_main_header">Schedule</span> <br />
    <hr />
	<iframe 
		src="https://www.google.com/calendar/embed?mode=AGENDA&amp;height=600&amp;wkst=2&amp;bgcolor=%23cccccc&amp;src=o2furkik0c2osb4u06ici87kn8%40group.calendar.google.com&amp;color=%23cccccc&amp;ctz=Europe%2FBerlin" 
		width="100%" 
		height="89%" 
		frameborder="0" 
		scrolling="no">
	</iframe>


<!-- should not put anything after this div -->
<?php
/* ---- include the footer file ---- */
include("footer.php")

?>
