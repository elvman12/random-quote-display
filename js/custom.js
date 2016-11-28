jQuery(document).ready(function($){
    //alert("Hello");
	// Add simple help text to each input box.
	console.log ('Realize the truth, there is no spoon.');
	$( '<p style="font-size:14px;">(Give credit where credit is due, enter the name of the person credited with this quote.)</p>' ).insertAfter( '#authorinput' );
	
	$( '<p style="font-size:14px;">(When entering the famous or inspirational quote, please do NOT add quotation marks.)</p>' ).insertAfter( '#quoteinput' );
	
	// Larger Help Section to explain shortcode usage.
	$( '<div id="rqd-instructions"><h2 class="hndle">Getting Random Quotes to Appear</h2><h3>Within Widgets</h3><p>To get a random quote from your database to appear in a widget, simply add the below shortcode into the widget:<br><br>[rqd-display]<br><br></p><h3>Within Template Files</h3><p>You can also get a random quote to display in your WordPress template files, such as footer.php or header.php by inserting the below php code into the template file of your choosing:<br><br>&lt;?php echo do_shortcode("[newshort]"); ?&gt;</p></div>' ).insertAfter( '#author-meta-box' );
	
	
});