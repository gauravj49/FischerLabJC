<?php 
	$root='/var/www/html/jcprod/';
	// this starts the session 
	session_start(); 
	include("../database.php");
	
	$paper_title = escapeshellcmd(htmlspecialchars($_SESSION[paper_title]));
	$link = htmlspecialchars($_SESSION[link]);
	$submitted_by = escapeshellcmd(htmlspecialchars($_SESSION[submitted_by]));
	$Summary = escapeshellcmd(htmlspecialchars($_SESSION[Summary]));
	//$Summary = str_replace("'", "\'", $Summary);
	$current = $_SESSION['currentState'];
	
	echo $current."<br /> <br />";
	
	while(list($key,$value) = each($_FILES["attachment"]["name"])) {
		$filename = $_FILES["attachment"]["name"][$key];
		$file_basename = substr($filename, 0, strripos($filename, '.')); // strip extention
		$new_basename  = preg_replace("/[^a-zA-Z0-9\_]/", "_", $file_basename);
		$file_ext = substr($filename, strripos($filename, '.')); // strip name
		$newfile = "$new_basename$file_ext";
		$paper_name.="$new_basename$file_ext|"; //Add all the file names
		// upload the files.
		if (file_exists("../docs/papers/" . $newfile)) {
			// file already exists error
			$error = "You have already submitted this file.";
		} else {
			echo $_FILES["attachment"]["tmp_name"][$key]."<br />";
			if (move_uploaded_file($_FILES["attachment"]["tmp_name"][$key], $root."docs/papers/$newfile")) {
				chmod($root."docs/papers/$newfile", 0755);
				echo "File uploaded successfully.<br />";
			}
			else {
				echo "File error.<br />";
			}
		}
	}
	$paper_name = substr_replace($paper_name ,"",-1); //remove last "|" from the file name
	
	# update the database
	if ($current == 1) {
		// remove the previous paper as current paper
		$sql="update current_entries set current=0 where current=1;";
		if (!mysql_query($sql)){
		  die('Error: ' . mysql_error());
		}
	}

	// insert the current paper
	$sql="INSERT INTO current_entries (paper_title, link, Summary, current, paper_name, submitted_by)
	VALUES ('$paper_title', '$link', '$Summary', $current, '$paper_name', '$submitted_by')";
	
	echo "$sql <br />";
	if (!mysql_query($sql)){
	  die('Error: ' . mysql_error());
	}

	//define the receiver of the email
	$to = 'gaurav.jain@dzne.de';

	//define the subject of the email
	$subject = 'Journal club Wiki - New paper submitted.';

	//define the message to be sent. Each line should be separated with \n
	$title_field = $_SESSION['paper_title'];
	$link_field = $_SESSION['link'];
	$abstract_field = $_SESSION['Summary'];
	$submitted_by_field = $_SESSION['submitted_by'];
	#$date = date("d m Y", time());

	$message = "
	<html>
	<body leftmargin=\"0\" marginwidth=\"0\" topmargin=\"0\" marginheight=\"0\" offset=\"0\" bgcolor=\'#202020\' >


	<STYLE>
	 .headerTop { background-color:#303030; border-top:0px solid #000000; border-bottom:1px solid #FFFFFF; text-align:center; }
	 .adminText { font-size:10px; color:#996600; line-height:200%; font-family:verdana; text-decoration:none; }
	 .headerBar { background-color:#FFFFFF; border-top:0px solid #333333; border-bottom:10px solid #FFFFFF; }
	 .title { font-size:20px; font-weight:bold; color:#CC6600; font-family:arial; line-height:110%; }
	 .subTitle { font-size:11px; font-weight:normal; color:#666666; font-style:italic; font-family:arial; }
	 .defaultText { font-size:12px; color:#000000; line-height:150%; font-family:trebuchet ms; }
	 .footerRow { background-color:#FFFFCC; border-top:10px solid #FFFFFF; }
	 .footerText { font-size:10px; color:#996600; line-height:100%; font-family:verdana; }
	 a { color:#FF6600; color:#FF6600; color:#FF6600; }
	</STYLE>

	<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" class=\"backgroundTable\" bgcolor=\'#202020\' >
		<tr>
			<td valign=\"top\" align=\"center\">
				<table width=\"550\" cellpadding=\"0\" cellspacing=\"0\">
					<tr>
						<td style=\"background-color:#FFCC66;border-top:0px solid #000000;border-bottom:1px solid #FFFFFF;text-align:center;\" align=\"center\"><span style=\"font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;\">Email not displaying correctly? <a href=\"go-svr-web01/jcprod/index.php\" style=\"font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;\">View it in your browser.</a></span></td>
					</tr>
					<tr>
						<td style=\"background-color:#C0C0C0;border-top:10px solid #C0C0C0;border-bottom:10px solid #C0C0C0;\"><center> <a href=\"go-svr-web01/jcprod/index.php\" ;> <font color=\"#000000\" size=\"16px\" style=\"italic\"; > JOURNAL CLUB </font> </a></center></td>
					</tr>
				</table>

				<table width=\"550\" cellpadding=\"20\" cellspacing=\"0\" bgcolor=\"#D0D0D0\">
					<tr>	
						<td bgcolor=\"#FFFFFF\" valign=\"top\" style=\"font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;\">
							<p>
								<span style=\"font-size:17px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;\">Paper Title</span><br>
								$title_field<br />
							</p>

							<p>
								<span style=\"font-size:17px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;\">Submitted By</span><br>
								$submitted_by_field<br />
							</p>

							<p>
							<span style=\"font-size:17px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;\">Paper Link</span><br>
							$link_field<br />
							</p>

							<p>
							<span style=\"font-size:17px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;\">Paper Abstract</span><br>
							$abstract_field<br />
							</p>
						</td>
					</tr>
					
					<tr>
						<td style=\"background-color:#FFFFCC;solid #FFFFFF;\">
							<span style=\"font-size:10px;color:#996600;line-height:100%;font-family:verdana;\">
								<a href =\"http://go-svr-web01/jcprod/index.php\" style=\'font-size:14px;\'>Link to the wiki</b></a> <br />
								<a href=\"mailto:gaurav.jain@dzne.de\"> Click here to unsubscribe</a> from this list.<br />
								Copyright (C) 2015 Fischer Lab. All Rights Reserved.
							</span>
						</td>
					</tr>
				</table>

			</td>
		</tr>
	</table>

	</body>
	</html>
	";


	//define the headers we want passed. Note that they are separated with \r\n
	  $headers = 'MIME-Version: 1.0' . "\r\n";
	  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	  
	/* Additional headers 
		$headers .= 'To: <gauravj49@gmail.com>,
						<gaurav.jain@dzne.de>' . "\r\n";

	/* Testing mode end and production list starts here...*/
	$headers .= 'To: <gauravj49@gmail.com>,
						<UserName2@emailhost.com>,
						<UserName3@emailhost.com>,
						<UserName4@emailhost.com>,
						<UserName5@emailhost.com>,
						<gaurav.jain@dzne.de>,' . "\r\n";

	$headers .= 'From:gaurav.jain@dzne.de' . "\r\n";

	//send the email
	
	$mail_sent = @mail( $to, $subject, $message, $headers );

	//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
	echo $mail_sent ? "Mail sent" : "Mail failed";


	header("Location: ../index.php");



?> 

