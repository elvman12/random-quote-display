jQuery(document).ready(function($){
	// Add simple help text to each input box.
	console.log ('Realize the truth, there is no spoon.');
	$( '<p style="font-size:14px;">(Give credit where credit is due, enter the name of the person credited with this quote.)</p>' ).insertAfter( '#authorinput' );

	$( '<p style="font-size:14px;">(Enter the quote or phrase of your choice, and there is no need to add quotation marks.)</p>' ).insertAfter( '#quoteinput' );
});