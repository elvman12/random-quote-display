<?php
/*
Plugin Name: Random Quote Display
Plugin URI:  Not applicable
Description: Readily add quotes and authors to your website.
Version:     1.0
Author:      Elvis Sherman
Author URI:  http://wwww.clicktimedesign.com/
License:     Pretty much free, enjoy.
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

define ( 'QQ_PLUGIN_PATH', plugin_dir_path(__FILE__) );

include ( QQ_PLUGIN_PATH . 'includes/post-type.php' );
include ( QQ_PLUGIN_PATH . 'includes/support.php' );
include ( QQ_PLUGIN_PATH . 'includes/shortcodes.php' );
include ( QQ_PLUGIN_PATH . 'includes/columns.php' );
include ( QQ_PLUGIN_PATH . 'includes/metaboxes.php' );

// Markup for the author input
function author_meta_box_markup() {
	global $post;
	$author_box_text = get_post_meta( $post->ID, 'author-box-text', true );
	$author_box_text = htmlentities($author_box_text, ENT_QUOTES);
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	?>
    
    <div id="authorinput">
    <input name="author-box-text" type="text" value="<?php echo "$author_box_text"; ?>"><br>
    </div> 
   
<?php    
}

// Markup for the quote input
function quote_meta_box_markup() {
	global $post;
	//$quote_box_text = get_post_meta( $post->ID, 'quote-box-text', true );
	$quote_title = get_the_title();
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	?>
    
    <div id="quoteinput">
    <input name="quote-box-text" type="text" value="<?php echo "$quote_title"; ?>"><br>    
    </div>
    
    <?php
}

// Now we need to save the entered data to the dbase when someone clicks save or publish
function rqd_save_quote($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    $slug = "quote";
    if($slug != $post->post_type)
        return $post_id;

    $author_box_text_value = "";
	$quote_box_text_value = "";	
 
    if(isset($_POST["author-box-text"]))
    {
		$author_box_text_value = sanitize_text_field($_POST["author-box-text"]);
	}   
    update_post_meta($post_id, "author-box-text", $author_box_text_value);
	
	if(isset($_POST["quote-box-text"]))
    {
		$quote_box_text_value = sanitize_text_field($_POST["quote-box-text"]);
	}   
    update_post_meta($post_id, "quote-box-text", $quote_box_text_value);
	
	global $wpdb;
	if ( get_post_type( $post_id ) == 'quote' ) {
		$quotetitle = get_post_meta($post_id, 'quote-box-text', true);
		$quotetitle = trim($quotetitle,'"');
		$where = array( 'ID' => $post_id );
		$wpdb->update( $wpdb->posts, array( 'post_title' => $quotetitle ), $where );
	}			
}
add_action("save_post", "rqd_save_quote", 10, 3);
?>