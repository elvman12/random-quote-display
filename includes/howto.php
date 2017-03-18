<?php
// Add Submenu Page for HowTo Information
function rqd_howto_page () {
	add_submenu_page( 'edit.php?post_type=quote', 'How To - RQD', 'How To', 'edit_pages', 'rqd_howto', 'rqd_display_howto' );
}
add_action('admin_menu', 'rqd_howto_page');

function rqd_display_howto () {
	echo "<div id=\"rqd-instructions\">";
	echo "<h3>Within Widgets</h3>";
	echo "<p>A random quote can be set to display very easily in a sidebar on your website. &nbsp;Simply select the standard Text Widget that comes with WordPress and apply it any sidebar (widgetized area) of your website, then add the below shortcode to the text widget.<br><br><span class=\"copythis\">[quickquote]</span><br><br></p>";
	echo "<h3>Within Template Files</h3>";
	echo "<p>You can also get a random quote to display in your WordPress template files, such as footer.php or header.php. &nbsp;Make sure to place the code snippet in the correct place within your template file so it appears correctly.<br><br><span class=\"copythis\">&lt;?php echo do_shortcode(\"[quickquote]\"); ?&gt;</span><br><br></p>";
	echo "<h3>Further Assistance</h3>";
	echo "<p>If you have questions or need further assistance, a more detailed summary of this plugin can be found <a href=\"#\">here</a>. &nbsp;If you are using this plugin and found it useful, trust me when I tell you a lot of work went into it. &nbsp;Please use the button below and enjoy the tremendous boost to your karma!<br></p>";
	echo "<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" target=\"_top\"><input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\"><input type=\"hidden\" name=\"hosted_button_id\" value=\"NFDT4U9HHG4H4\"><input type=\"image\" src=\"https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif\" border=\"0\" name=\"submit\" alt=\"PayPal - The safer, easier way to pay online!\"><img alt=\"\" border=\"0\" src=\"https://www.paypalobjects.com/en_US/i/scr/pixel.gif\" width=\"1\" height=\"1\"></form>";
	echo "</div>";
}
?>