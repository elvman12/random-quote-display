<?php
/*
Plugin Name: Random Quote Display
Plugin URI:  Not applicable
Description: Custom Post Type to record quotes and credit names.
Version:     1.0
Author:      Elvis Sherman
Author URI:  http://wwww.clicktimedesign.com/
License:     Pretty much free, enjoy.
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

add_action( 'init', 'ctd_famous_quotes' );

function ctd_famous_quotes() {	
	$labels = array(
		'name'               => 'Quotes',
		'singular_name'      => 'Quote',
		'menu_name'          => 'Quote',
		'name_admin_bar'     => 'Quote',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Quote',
		'new_item'           => 'New Quote',
		'edit_item'          => 'Edit Quote',
		'view_item'          => 'View Quote',
		'all_items'          => 'All Quotes',
		'search_items'       => 'Search Quotes',
		'parent_item_colon'  => 'Parent Quotes:',
		'not_found'          => 'No Quotes found.',
		'not_found_in_trash' => 'No Quotes found in Trash.'
	);
	
	$args = array( 
		'public' => true,
		'hierarchical' => false,
		'labels' => $labels,
		'publicly_queryable' => false,
		'exclude_from_search' => false,
		'rewrite' => array( 'slug' => 'quote' ),
		'menu_position' => 5,
		'show_ui' => true,
		'slug' => 'quote',
		'show_in_admin_bar' => true,
		'taxonomies' => array( 'category' ),
		'menu_icon' => 'dashicons-format-quote',
		'supports' => false // This line removes the default metaboxes for Title and Editor fields
	);
	
	register_post_type( 'quote', $args );
}

// Plugin Activation
function ctd_flush_rewrites() {
	ctd_famous_quotes();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ctd_flush_rewrites' );

// Plugin Deactivation
function ctd_flush_rewrites_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ctd_flush_rewrites_deactivate' );



// Disable the annoying autosave feature on this post type 
function my_admin_enqueue_scripts() {
  switch(get_post_type()) {
    case 'quote':
      wp_dequeue_script('autosave');
      break;
  }
}
add_action('admin_enqueue_scripts', 'my_admin_enqueue_scripts');






// Adding some jquery to the plugin, the right way to ONLY affect this custom post type!!!
// All this code does is connect to the external js file contained in the plugin.
function wpse_cpt_enqueue( $hook_suffix ){
    $cpt = 'quote';

    if( in_array($hook_suffix, array('post.php', 'post-new.php') ) ){
        $screen = get_current_screen();

        if( is_object( $screen ) && $cpt == $screen->post_type ){
            // Register, enqueue scripts and styles here
			wp_enqueue_script(  'myscript', plugins_url('random-quote-display/js/custom.js') );
        }
    }
}
add_action( 'admin_enqueue_scripts', 'wpse_cpt_enqueue');







// Here is where we add the Meta Boxes to add our custom fields and data.
// As you can see each meta box requires a function that defines the HTML Markup, or what is contained in the meta box.

// Markup for the author input
function author_meta_box_markup() {
	global $post;
	$author_box_text = get_post_meta( $post->ID, 'author-box-text', true );
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	?>
    
    <div id="authorinput">
    <input style="width:30%;" name="author-box-text" type="text" value="<?php echo "$author_box_text"; ?>"><br>
    </div> 
   
<?php    
}

// Markup for the quote input
function quote_meta_box_markup() {
	global $post;
	$quote_box_text = get_post_meta( $post->ID, 'quote-box-text', true );
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	?>
    
    <div id="quoteinput">
    <input style="width:100%;" name="quote-box-text" type="text" value="<?php echo "$quote_box_text"; ?>"><br>
    <!--<input type="hidden" name="post_title"value="<?php echo "$quote_box_text"; ?>" id="title" />-->
    </div> 
   
<?php    
}



// This is what makes the meta boxes actually appear
function add_custom_meta_box()
{    
	add_meta_box("quote-meta-box", "Enter the Quote", "quote_meta_box_markup", "quote", "normal", "low", null);
	add_meta_box("author-meta-box", "Author Name", "author_meta_box_markup", "quote", "normal", "low", null);
}
add_action("add_meta_boxes", "add_custom_meta_box");

// Remove Unnecessary Metaboxes
function ctd_remove_meta_stuff() {
    remove_meta_box( 'sharing_meta' , 'quote' , 'advanced' );
	remove_meta_box( 'wpseo_meta' , 'quote' , 'normal' );
}
add_action('do_meta_boxes','ctd_remove_meta_stuff');





// Now we need to save the entered data to the dbase when someone clicks save or publish
function save_custom_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    //if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        //return $post_id;

    $slug = "quote";
    if($slug != $post->post_type)
        return $post_id;

    $author_box_text_value = "";
	$quote_box_text_value = "";    

    if(isset($_POST["author-box-text"]))
    {
        $author_box_text_value = $_POST["author-box-text"];
    }   
    update_post_meta($post_id, "author-box-text", $author_box_text_value);
	
	if(isset($_POST["quote-box-text"]))
    {
        $quote_box_text_value = $_POST["quote-box-text"];		
    }   
    update_post_meta($post_id, "quote-box-text", $quote_box_text_value);			
}
add_action("save_post", "save_custom_meta_box", 10, 3);




// Let's change the title to something other than Auto Draft
function ctd_set_title ( $post_id ) {
    global $wpdb;
    if ( get_post_type( $post_id ) == 'quote' ) {
        $quotetitle = get_post_meta($post_id, 'quote-box-text', true);
		$quoteauthor = get_post_meta($post_id, 'author-box-text', true);
        $title = $quotetitle . " &nbsp;&nbsp;(" . $quoteauthor . ")";
        $where = array( 'ID' => $post_id );
        $wpdb->update( $wpdb->posts, array( 'post_title' => $title ), $where );
    }
}
add_action( 'save_post', 'ctd_set_title', 100 );




// Improvements for next IDP....

// Create shortcode for user-friendliness, so it will be easier to add into widgets etc.
// Create custom taxonomy for this custom post type only (or ability for people to create their own category)
// Remove dbase overhead... (remove title from postmeta table since its a duplicate)
// Add separate stylesheet for this plugin, or leave that up to who uses it?
// Other uses...  This would great to display random customer testimonials on a site.
// A custom post type would be great for our team site... a new way to add release notes, or incident reports, etc.
?>