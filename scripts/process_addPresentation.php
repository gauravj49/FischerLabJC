<?php
	// this starts the session 
	session_start(); 
	include("../database.php");

	$config = array();
	// Show all errors except the notice ones
	error_reporting(E_ALL ^ E_NOTICE);

	header('Cache-control: index'); // IE 6 FIX
	// check for empty fields
	$post_presentation      = $_POST['presentation_title'];
	$post_presented_by      = $_POST['presented_by'];
	$post_presented_on      = $_POST['presented_on'];
	$post_presentation_type = $_POST['presentation_type'];
	$post_month             = $_POST['month'];
	$post_day               = $_POST['day'];
	$post_year              = $_POST['year'];
	$post_presented_on      = $post_year."-".$post_month."-".$post_day;
	if (is_array($post_presented_by)) { 
		for ($i=0;$i<count($post_presented_by);$i++) { 
			$string_presented_by .= "$post_presented_by[$i] & "; 
		} 
		// remove last "_" from the file name
		$string_presented_by = rtrim($string_presented_by, " & ");
	}
	
	/*
		echo "<pre>";
		print_r($_POST); 
		var_dump(checkdate($post_month,$post_day,$post_year));
		echo "</pre>";
		
		//example output
		Array
		(
			[presentation_title] => Sequence comparison and alignment
			[presented_by] => Array
				(
					[0] => Gaurav Jain
					[1] => User Name1
				)

			[presentation_type] => Bio-Informatics
			[add] => Submit Presentation
		)
		Gaurav Jain_User Name1
	*/
	if($post_presentation != ''&& $post_presented_by!= '' && $post_presented_on!= '' && $post_presentation_type!=''&& checkdate($post_month,$post_day,$post_year)){ 
		$_SESSION['presentation_title'] = $post_presentation;
		$_SESSION['presented_by']       = $string_presented_by;
		$_SESSION['presented_on']       = $post_presented_on;
		$_SESSION['presentation_type']  = $post_presentation_type;
		// echo 'OK'; // this response is checked.
		include("presentation_redirect.php");
	}else{
		/* ---- Set page title and description ---- */
		$page_title = "";
		$page_description = "";
		echo "test";
		/* ---- include the header file ---- */
		include("../header.php");

		/* ---- include the database connection script ---- */
		include("../database.php");
		$err_message .= "The presentaton info is not complete.<br />Please add the necessary information.<br />";
		$err_message .= " Incomplete Information: ";
		
		if($post_presentation == ''){
			$err_message .= "<li>Presentation title</li>";
		}
		
		if($post_presented_by == ''){
			$err_message .= "<li>Presented By</li>";
		}
		
		if($post_presentation_type == ''){
			$err_message .= "<li>Presentation type</li>";
		}
		
		if(!checkdate($post_month,$post_day,$post_year)){
			$err_message .= "<li>The date you have entered is invalid. Please enter a correct date.</li>";
		}
	}
	?>
	<div class="pageContainer">

	<div class="pageContent" style="width:100%;">
	<span id="body_main_header">INCOMPLETE INFORMATION</span> <br />
	<hr />
	<!-- should not put anything before this -->
	<div id="notification_error"><?php echo "$err_message";?></div>
	 </div> <!-- end of div inside pageContainer div. -->

	<hr style="margin-top:10px"/>
	  <div id="nav_menu">
		  <div id="shelf">
			<ul>  
			   <li style="margin:0 40px 0 60px;"><a href="../index.php" title="Journal Club Home">Home</a></li>  
			   <li><a id="add_link" href="../addPaper.php" title="Submit a paper of interest or the paper you want to present">ADD a paper</a></li>  
			   <li><a href="../aboutus.php" title="Facts about journal club">About us</a></li>  
			   <li><a href="../schedule.php" title="Schedule"> Schedule</a></li>  
			   <li><a href="../papers.php" title="All the papers which are not presented or just interesting to read">Papers</a></li>  
			   <li style="margin-right:110px;"><a href="../previousPresentations.php" title="All the papers and presentation which are presented earlier in the journal club">Previous presentations</a></li>  
			</ul> 
		  </div> 
	  </div> <!-- end of nav menu div. -->

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