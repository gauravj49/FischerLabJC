// Preload Images
img1 = new Image(16, 16);  
img1.src="images/spinner.gif";

img2 = new Image(220, 19);  
img2.src="images/ajax-loader.gif";

// When DOM is ready
$(document).ready(function(){

// Launch MODAL BOX if the ADD PAPER Link is clicked
$("#add_link").click(function(){
$('#add_form').modal();
});

// When the form is submitted
$("#status > form").submit(function(){  

// Hide 'Submit' Button
$('#submit').hide();

// Show Gif Spinning Rotator
$('#ajax_loading').show();

// 'this' refers to the current submitted form  
var str = $(this).serialize();  

// -- Start AJAX Call --

$.ajax({  
    type: "POST",
    url: "scripts/check_parameters.php",  // Send the paper info to this page
    data: str,  
    success: function(msg){  
   
$("#status").ajaxComplete(function(event, request, settings){  
 
 // Show 'Submit' Button
$('#submit').show();

// Hide Gif Spinning Rotator
$('#ajax_loading').hide();  

 if(msg == 'OK') // ADD PAPER OK?
 {  
	 var add_response = '<div id="add_in">' +
		 '<div style="width: 350px; float: left; margin-left: 70px;">' + 
		 '<div style="width: 40px; float: left;">' +
		 '<img style="margin: 10px 0px 10px 0px;" align="absmiddle" src="images/ajax-loader.gif">' +
		 '</div>' +
		 '<div style="margin: 10px 0px 0px 10px; float: right; width: 300px;">'+ 
		 "Your paper is added successfully! <br /> Please wait while you're redirected...</div></div>";  

	$('a.modalCloseImg').hide();  

	$('#simplemodal-container').css("width","500px");
	$('#simplemodal-container').css("height","120px");
	 
	 $(this).html(add_response); // Refers to 'status'

	// After 3 seconds redirect the 
	setTimeout('go_to_main_page()', 3000); 
 }  
 else // ERROR?
 {  
	 var add_response = msg;
	 $('#add_response').html(add_response);
	 }  
      
 });  
   
 }  
   
  });  
  
// -- End AJAX Call --

return false;

}); // end submit event

});

function go_to_main_page()
{
window.location = 'scripts/redirect.php'; // insert into database page
}