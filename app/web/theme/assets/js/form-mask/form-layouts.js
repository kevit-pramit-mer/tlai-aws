/*
 * Form Layouts - Form
 */

$(document).ready(function () {
$( ".datepicker" ).each(function( index ) {
	if($( this ).val() != "") { $( this ).datepicker( {"format":'yyyy-mm-dd'} ).datepicker("setDate", $( this ).val()); }
	else { $( this ).datepicker( {"format":'yyyy-mm-dd'} ).datepicker("setDate", new Date()); }	
});	
})
