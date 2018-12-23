<?php
include("../database.php");
header('Content-type: application/json');  // this is the magic that sets responseJSON

$rows = array();
if(isset($_GET['presented_by'])) {
	$name = $_GET['presented_by'];

	/* PULL OUT THE DATA FOR THE SELECTED PRESENTER */
	$q = "select paper_title from current_entries where submitted_by = $name ORDER BY id DESC";
	$rs = mysqli_query(connect_to_db(),$q);
	if (!$rs) {
		echo "Could not execute query: $query";
		trigger_error(mysqli_error(), E_USER_ERROR); 
	} 

	while ($row = mysqli_fetch_assoc($rs)) {
		$rows[] = array("presentation_title" => $row["paper_title"]);
	}
	echo json_encode($rows);
}

?>
