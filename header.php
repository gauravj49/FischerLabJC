<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php echo $page_title; ?></title>
	<meta http-equiv="description" content="<?php echo $page_description; ?>" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="images/favicon.ico" />
   	<link rel="stylesheet" type="text/css" href="css/common.css" />
	<script type="text/javascript" src="js/sorttable.js"></script> 
	<link rel="stylesheet" type="text/css" href="css/countDownStyle.css" />
	
	
	<!-- this cssfile can be found in the jScrollPane package -->
    <link rel="stylesheet" type="text/css" href="css/jquery.jscrollpane.css" />
    <!-- latest jQuery direct from google's CDN -->
    <script type="text/javascript" src="js/jquery-1.9.0.js"></script>
    <!-- the jScrollPane script -->
    <script type="text/javascript" src="js/jquery.jscrollpane.min.js">
    <!--instantiate after some browser sniffing to rule out webkit browsers-->
      $(document).ready(function () {
          if (!$.browser.webkit) {
              $('.container').jScrollPane();
          }
      });
    </script>
</head>

<body>
	<div id="wrap">
		<div id="header">
			<div id="headerDate">
				 <? print(Date("l F d, Y")); ?> 
			</div>
			
			<div id="headerText"> 
	 			<a href="index.php">journal club</a> 
			</div>  
			
			<div class="c1"></div>

			<div id="navbar">
				<ul>
				   <li><a href="index.php" title="Journal Club Home">Home</a></li>  
				   <li><a id="add_link" href="addPaper.php" title="Submit a paper of interest or the paper you want to present">ADD-paper</a></li>  
				   <li><a href="aboutus.php" title="Facts about journal club">About us</a></li>  
				   <li><a href="schedule.php" title="Schedule"> Schedule</a></li>  
				   <li><a href="papers.php" title="All the papers which are not presented or just interesting to read">Papers</a></li>  
				   <li><a href="previousPresentations.php" title="All the papers and presentation which are presented earlier in the journal club">Archive</a></li>  
				</ul> 
			</div>
			
	   </div> <!-- end of header div -->
		   	   

