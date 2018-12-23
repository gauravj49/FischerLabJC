<?php
/* ---- Set page title and description ---- */
$page_title = "Add a paper : Journalclub";
$page_description = "Add a new paper of interest or the one which is going to be present this week in journalclub";

/* ---- include the header file ---- */
include("header.php");

/* ---- include the database connection script ---- */
include("database.php");
?>
<script type="text/javascript">
	var upload_number = 1; 
	function addFileInput(which_name, which_id) { 
		var d = document.createElement("div"); 
		var file = document.createElement("input"); 
		file.setAttribute("type", "file"); 
		file.setAttribute("name", which_name + "["+upload_number+"]"); 
		d.appendChild(file); 
		document.getElementById(which_id).appendChild(d); 
		upload_number++; 
	}
	
	function get_current_state(){
		if (confirm('Are you going to present this paper? \n\n If yes, please press \"OK\" else press \"Cancel\".')){
			document.getElementById("currentState").value = 1;
		}else{
			document.getElementById("currentState").value = 0;
			alert("Your paper is submitted to the website but not active for this week.\n\n You can view your paper from the \"Papers\" tab.")
		}
		
	}
	
	function resetValues() {
		$('#presentation_title').empty();
		$('#presentation_title').append(new Option('Please select', '', true, true));
		$('#presentation_title').attr("disabled", "disabled");
		// $('#printermodel').empty();
		// $('#printermodel').append(new Option('Please select', '', true, true));
		// $('#printermodel').attr("disabled", "disabled");	
	}

	$(document).ready(function() {
		// Checkboxes click function
			// http://jsfiddle.net/F29Mv/1/
			// http://stackoverflow.com/questions/13764087/jquery-php-ajax-filter-based-on-multiple-checkbox-array
			// $('input[type="checkbox"]').on('click',function(){ # for all the checkboxes in the page
		$('input[name="presented_by[]"]').on('click',function(){
			// This will make a new dropdown list element and reset it to default everytime someone check a box
			resetValues();
			
			// Here we check which boxes in the same group have been selected
			var checked = [];
			
			// Loop through the checked checkboxes in the same group and add their values to an array
			$(':checked').each(function(){
				checked.push($(this).val());
			});
			
			// Remove the last 3 items(00,00,00) from the array 
				// original checked = Gaurav Jain,User Name1,00,00,00,none
				// after removing   = Gaurav Jain,User Name1
			checked.splice(checked.length - 4);
			
			// for more than one presenter create Gaurav Jain & User Name1
			presenter_name = "";
			for(i=0; i< checked.length; i++){
				presenter_name = presenter_name + checked[i] + " & ";
			}
			presenter_name = presenter_name.slice(0,-3); // remove last < & >
			populatePresentationTitle(presenter_name);
		});

		function populatePresentationTitle(which_name) {
			// alert(which_name);
			resetValues();
			$.getJSON("scripts/json_get_presentaion_info.php",{presented_by:"\"" + which_name + "\""}, function(data) {
				if(data) {
					$('#presentation_title').removeAttr('disabled'); 
					var select = $('#presentation_title');
					var options = select.attr('#presentation_title');
					if(data.length > 0){
						$.each(data, function(index, array) {
							// http://stackoverflow.com/questions/1536396/adding-values-to-a-dropdown-select-box-using-jquery
							var newOption = $('<option>');
							newOption.attr('value',array.index).text(array.presentation_title);
							 $('#presentation_title').append(newOption);
							
							// reset the dropdown when the checkbox is unchecked.
							if(array.presentation_title == 'No Journal Club Meeting'){
								resetValues();
							}
						});
					}else{
						alert("You have not presented any paper.\nPlease submit atleast one paper first you have presented.");
						resetValues();
					}
				}else {
					alert('error');
				}
			});
		} // end of popolatePresentationTitle
		

	});
</script>

<div class="pageContainer">

  <div class="pageContent" style="width:100%; height:1100px;">
  
    <span id="body_main_header">Add a paper</span> <br />
    <hr />
	<!-- should not put anything before this -->
	<div id="paper_data">
	<table width="100%" border="0" align="left">
		<tr><td>
			<form  id="addPaper" method="post" enctype="multipart/form-data" action="scripts/process_addPaper.php" style="font-size:20px">
				<table border="0" cellpadding="10">
					<tr>
						<td width="100px">Paper Title *</td>
						<td width="2%">:</td>
						<td>
								<input type="text" name="paper_title" size="64px"/>
						</td>
					</tr>
					
					<tr>
						<td>Link *</td>
						<td width="2%">:</td>
						<td>
							<input type="text" name="link"  size="64px"/><br />
						</td>
					</tr>
						
					<tr>
						<td>Abstract *</td>
						<td width="2%">:</td>
						<td>
							<textarea name="Summary" cols="45" rows="5"></textarea>
						</td>
					</tr>

					<tr>
						<td>Submitted By *</td>
						<td width="2%">:</td>
						<td> <p style="height: 95px; overflow: auto; border: 1px solid #ddd; color: #000; margin-bottom: 1.5em;" class="container">
							<label><input type="checkbox" name="submitted_by[]" value="Gaurav Jain" />Gaurav Jain</label><br />
							<label><input type="checkbox" name="submitted_by[]" value="User Name2" />User Name2</label><br />
							<label><input type="checkbox" name="submitted_by[]" value="User Name3" />User Name3</label><br />
							<label><input type="checkbox" name="submitted_by[]" value="User Name4" />User Name4</label><br />
							<label><input type="checkbox" name="submitted_by[]" value="User Name5" />User Name5</label><br />
							</p>
						</td>
					</tr>
					
					<tr>
						<td>Choose File To Upload </td>
						<td width="2%">:</td>
						<td>
							<input type="file" name="attachment[]" id="attachment" onchange="document.getElementById('moreUploadsLink').style.display = 'inline';" /> 
							<div id="moreUploads"></div> 
							<div id="moreUploadsLink" style="display:none;"><ul><li><a href="javascript:addFileInput('attachment','moreUploads');" class="noDocLinks" >Attach another File</a></li></ul></div>
						</td>
					</tr>
					
					<tr>
						<td>
							<input type="hidden" name="currentState" id="currentState" value="0" />
							<input value="Submit Paper" name="add" id="submit"  style="color: #fff; background: #555; width:180px; font-size:24px;" type="submit" onclick="get_current_state(currentState)"/>
						</td>
					</tr>
				</table>
			</form>
		</td></tr>
	</table>
	</div> <!-- Add Paper section ends here -->

	<span style="color: #686868; font-size: 40px;  float:right;">Add a presentation</span>
	<div>&nbsp;</div>
	<div id="presentaton_data" >
	<hr style="margin-top:45px; width:100%"/>
	<table border="0" align="left">
		<tr><td>
			<form  id="addPresentation" method="post" enctype="multipart/form-data" action="scripts/process_addPresentation.php" style="font-size:20px">
				<table border="0" cellpadding="5">
					<tr>
						<td>Presented By *</td>
						<td width="2%">:</td>
						<td> <p style="height: 95px; overflow: auto; border: 1px solid #ddd; color: #000; margin-bottom: 1.5em;" class="container">
							<label><input type="checkbox" name="presented_by[]" id="presented_by[]" value="Gaurav Jain" />Gaurav Jain</label><br />
							<label><input type="checkbox" name="presented_by[]" id="presented_by[]" value="User Name2" />User Name2</label><br />
							<label><input type="checkbox" name="presented_by[]" id="presented_by[]" value="User Name3" />User Name3</label><br />
							<label><input type="checkbox" name="presented_by[]" id="presented_by[]" value="User Name4" />User Name4</label><br />
							<label><input type="checkbox" name="presented_by[]" id="presented_by[]" value="User Name5" />User Name5</label><br />
							</p>
						</td>
					</tr>
					
					<tr>
						<td>Presentation Title *</td>
						<td width="2%">:</td>
						<td  class="presentationTitle">
							<select name="presentation_title" id="presentation_title" width = 90%  disabled>
								<option value="None">-- Select --</option>
							</select>
							
							<!-- <input type="text" id="presentation_title" style="display:none;" /> -->
						</td>
					</tr>
					
					<tr>
						<td>Presented On *</td>
						<td width="2%">:</td>
						<td>
							<select name="day" id="day">
								<option value="00">Day</option>
								<option value="01st">1</option><option value="02nd">2</option>
								<option value="03rd">3</option><option value="04th">4</option>
								<option value="05th">5</option><option value="06th">6</option>
								<option value="07th">7</option><option value="08th">8</option>
								<option value="09th">9</option><option value="10th">10</option>
								<option value="11th">11</option><option value="12th">12</option>
								<option value="13th">13</option><option value="14th">14</option>
								<option value="15th">15</option><option value="16th">16</option>
								<option value="17th">17</option><option value="18th">18</option>
								<option value="19th">19</option><option value="20th">20</option>
								<option value="21st">21</option><option value="22nd">22</option>
								<option value="23rd">23</option><option value="24th">24</option>
								<option value="25th">25</option><option value="26th">26</option>
								<option value="27th">27</option><option value="28th">28</option>
								<option value="29th">29</option><option value="30th">30</option>
								<option value="31st">31</option>
							</select>
							
							<select name="month" id="month"> 
								<option value="00" >Month</option>
								<option value="01">January</option>
								<option value="02">February</option>
								<option value="03">March</option>
								<option value="04">April</option>
								<option value="05">May</option>
								<option value="06">June</option>
								<option value="07">July</option>
								<option value="08">August</option>
								<option value="09">September</option>
								<option value="10">October</option>
								<option value="11">November</option>
								<option value="12">December</option>
							</select>
							
							<select name="year" id="year">
								<option value="00">Year</option>
								<option value="2011">2011</option>
								<option value="2012">2012</option>
								<option value="2013">2013</option>
								<option value="2014">2014</option>
								<option value="2015">2015</option>
								<option value="2016">2016</option>
								<option value="2017">2017</option>
								<option value="2018">2018</option>
								<option value="2019">2019</option>
								<option value="2019">2020</option>
							</select>
						</td>
					</tr>

					<tr>
						<td>Presentation Type *</td>
						<td width="2%">:</td>
						<td>
							<input type="radio" name="presentation_type" id="presentation_type" value="Biology" />Biology
							<input type="radio" name="presentation_type" id="presentation_type" value="Statistics" />Statistics
							<input type="radio" name="presentation_type" id="presentation_type" value="BioInformatics" />BioInformatics
						</td>
					</tr>
					
					<tr>
						<td>Choose File To Upload </td>
						<td width="2%">:</td>
						<td>
							<input type="file" name="presentation[]" id="presentation" onchange="document.getElementById('morePUploadsLink').style.display = 'inline';" /> 
							<div id="morePUploads"></div> 
							<div id="morePUploadsLink" style="display:none;"><ul><li><a href="javascript:addFileInput('presentation','morePUploads');" class="noDocLinks" >Attach another File</a></li></ul></div>
						</td>
					</tr>
					
					<tr>
						<td>
							<br />
							<input value="Submit Presentation" name="add" id="Psubmit"  style="color: #fff; background: #555; width:240px; font-size:24px;" type="submit"/>
						</td>
					</tr>
				</table>
			</form>
		</td></tr>
	</table>
	</div> <!-- Add Presentation section ends here -->
	<!-- should not put anything after this div -->
<?php
/* ---- include the footer file ---- */
include("footer.php")

?>
