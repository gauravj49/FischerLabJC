<?php
	$config = array();
	// Show all errors except the notice ones
	error_reporting(E_ALL ^ E_NOTICE);
	session_start();
	header('Cache-control: index'); // IE 6 FIX
	// check for empty fields
	$post_paper = $_POST['paper_title'];
	$post_link= $_POST['link'];
	$post_summary = $_POST['Summary'];
	$post_current = $_POST['currentState'];
	$post_submitted_by = $_POST['submitted_by'];
	if (is_array($post_submitted_by)) { 
		for ($i=0;$i<count($post_submitted_by);$i++) { 
			$string_submitted_by .= "$post_submitted_by[$i] & "; 
		} 
		// remove last "_" from the file name
		$string_submitted_by = rtrim($string_submitted_by, " & ");
	}

	if($post_paper != ''&& $post_link!= '' && $post_summary!='' && $post_submitted_by!=''){ 
		$_SESSION['paper_title'] = $post_paper;
		$_SESSION['link'] = $post_link;
		$_SESSION['comments']= $_POST['comments'];
		$_SESSION['Summary']= $_POST['Summary'];
		$_SESSION['currentState']= $post_current;
		$_SESSION['submitted_by']= $string_submitted_by;
		//echo 'OK'; // this response is checked.
		echo "<div> current = $post_current <br /></div>";
		include("redirect.php");
	}else{
		/* ---- Set page title and description ---- */
		$page_title = "";
		$page_description = "";

		/* ---- include the header file ---- */
		include("../header.php");

		/* ---- include the database connection script ---- */
		include("../database.php");
	}
	?>
	<div class="pageContainer">

	<div class="pageContent" style="width:100%;">
	<span id="body_main_header">INCOMPLETE INFORMATION</span> <br />
	<hr />
	<!-- should not put anything before this -->
	<div id="notification_error">The paper info is not complete.<br /> Please add the necessary information.</div>
	

	 </div> <!-- end of div inside pageContainer div. -->

	<hr style="margin-top:10px"/>
	</div> <!-- end of pageContainer div. -->
		<div id="footer">
			<div id="footerContent"> 
				 &copy; <a href="http://www.fischerlab.uni-goettingen.de/" target="_blank" title="link to Fischer lab website" class="noDocLinks" style="font-size:14px"> 2018 Fischer Lab</a>. All Rights Reserved .
			</div>

			<div id="footerValidationIcons">
			<p>
				<a href="http://jigsaw.w3.org/css-validator/check/referer" target="_blank">
				  <img src="http://jigsaw.w3.org/css-validator/images/vcss-blue"
					 alt="Valid CSS!" />
						</a>

				<a href="http://validator.w3.org/check/referer" target="_blank">
				   <img src="http://www.w3.org/Icons/valid-xhtml11-blue"
				   alt="Valid XHTML 1.0!" />
				</a>
			</p>
		   </div> <!-- end of footerValidationIcons div -->
			  
		</div> <!-- end of footer div -->
	   </div> <!-- end of wrap div -->
	  </body>
	</html>