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
		if (!mysqli_query(connect_ToEmails_db(),$sql)){
		  die('Error: ' . mysqli_error());
		}
	}

	// insert the current paper
	$sql="INSERT INToEmails current_entries (paper_title, link, Summary, current, paper_name, submitted_by)
	VALUES ('$paper_title', '$link', '$Summary', $current, '$paper_name', '$submitted_by')";
	
	echo "$sql <br />";
	if (!mysqli_query(connect_ToEmails_db(),$sql)){
	  die('Error: ' . mysqli_error());
	}

	//define the subject of the email
	$subject = 'Journal club Wiki - New paper submitted.';

	//define the message ToEmails be sent. Each line should be separated with \n
	$title_field = $_SESSION['paper_title'];
	$link_field = $_SESSION['link'];
	$abstract_field = $_SESSION['Summary'];
	$submitted_by_field = $_SESSION['submitted_by'];
	#$date = date("d m Y", time());

	$message = "
	<html>
	<body leftmargin=\"0\" marginwidth=\"0\" ToEmailspmargin=\"0\" marginheight=\"0\" offset=\"0\" bgcolor=\'#202020\' >


	<STYLE>
	 .headerToEmailsp { background-color:#303030; border-ToEmailsp:0px solid #000000; border-botToEmailsm:1px solid #FFFFFF; text-align:center; }
	 .adminText { font-size:10px; color:#996600; line-height:200%; font-family:verdana; text-decoration:none; }
	 .headerBar { background-color:#FFFFFF; border-ToEmailsp:0px solid #333333; border-botToEmailsm:10px solid #FFFFFF; }
	 .title { font-size:20px; font-weight:bold; color:#CC6600; font-family:arial; line-height:110%; }
	 .subTitle { font-size:11px; font-weight:normal; color:#666666; font-style:italic; font-family:arial; }
	 .defaultText { font-size:12px; color:#000000; line-height:150%; font-family:trebuchet ms; }
	 .footerRow { background-color:#FFFFCC; border-ToEmailsp:10px solid #FFFFFF; }
	 .footerText { font-size:10px; color:#996600; line-height:100%; font-family:verdana; }
	 a { color:#FF6600; color:#FF6600; color:#FF6600; }
	</STYLE>

	<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" class=\"backgroundTable\" bgcolor=\'#202020\' >
		<tr>
			<td valign=\"ToEmailsp\" align=\"center\">
				<table width=\"550\" cellpadding=\"0\" cellspacing=\"0\">
					<tr>
						<td style=\"background-color:#FFCC66;border-ToEmailsp:0px solid #000000;border-botToEmailsm:1px solid #FFFFFF;text-align:center;\" align=\"center\"><span style=\"font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;\">Email not displaying correctly? <a href=\"go-svr-web01/jcprod/index.php\" style=\"font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;\">View it in your browser.</a></span></td>
					</tr>
					<tr>
						<td style=\"background-color:#C0C0C0;border-ToEmailsp:10px solid #C0C0C0;border-botToEmailsm:10px solid #C0C0C0;\"><center> <a href=\"go-svr-web01/jcprod/index.php\" ;> <font color=\"#000000\" size=\"16px\" style=\"italic\"; > JOURNAL CLUB </font> </a></center></td>
					</tr>
				</table>

				<table width=\"550\" cellpadding=\"20\" cellspacing=\"0\" bgcolor=\"#D0D0D0\">
					<tr>	
						<td bgcolor=\"#FFFFFF\" valign=\"ToEmailsp\" style=\"font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;\">
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
								<a href =\"http://go-svr-web01/jcprod/index.php\" style=\'font-size:14px;\'>Link ToEmails the wiki</b></a> <br />
								<a href=\"mailToEmails:gaurav.jain@dzne.de\"> Click here ToEmails unsubscribe</a> from this list.<br />
								Copyright (C) 2018 Fischer Lab. All Rights Reserved.
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
	
	$message_text="\nPaper Title: ".$title_field."\nSubmitted By ".$submitted_by_field."\nPaper Link: ".$link_field."\n\nPaper Abstract\n".$abstract_field;

		
	# Write the message in the file
    $ToEmails                 .= 'emailList@emailhost.com';
	$additionalToEmailsEmails .= 'gauravj49@gmail.com,username2@emailhost.com,username3@emailhost.com,username4@emailhost.com,username5@emailhost.com';
	
	//send the email
	exec('pymail -s "'.$subject.'" -b "'.$message_text.'" -n "emailList@emailhost.com" -r '.$ToEmails);
	exec('pymail -s "'.$subject.'" -b "'.$message_text.'" -n "emailList@emailhost.com" -r '.$additionalToEmailsEmails);
	
	//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
	//echo $mail_sent ? "Mail sent" : "Mail failed";

	# Redirect ToEmails the home page
	header("Location: ../index.php");
?> 

