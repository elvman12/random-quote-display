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
		'exclude_from_search' => true,
		'rewrite' => array( 'slug' => 'quote' ),
		'menu_position' => 5,
		'show_ui' => true,
		'slug' => 'quote',
		'show_in_admin_bar' => true,
		'taxonomies' => array( 'category' ),
		'menu_icon' => 'dashicons-format-quote',
		'supports' => array ( 'title' )
		//'supports' => false // This line removes the default metaboxes for Title and Editor fields
	);
	
	register_post_type( 'quote', $args );
}
add_action( 'init', 'ctd_famous_quotes' );








// Adding some jquery to the plugin, the right way!!!
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



// This is what makes the meta boxes actually appear
function add_custom_meta_box()
{    
	//add_meta_box("quote-meta-box", "Quote", "quote_meta_box_markup", "quote", "normal", "high", null);
	add_meta_box("author-meta-box", "Author Name", "author_meta_box_markup", "quote", "normal", "low", null);
}
add_action("add_meta_boxes", "add_custom_meta_box");






// Now we need to save the entered data to the dbase when someone clicks save or publish
function save_custom_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "quote";
    if($slug != $post->post_type)
        return $post_id;

    $author_box_text_value = "";
	//$quote_box_text_value = "";    

    if(isset($_POST["author-box-text"]))
    {
        $author_box_text_value = $_POST["author-box-text"];
    }   
    update_post_meta($post_id, "author-box-text", $author_box_text_value);
	
	//if(isset($_POST["quote-box-text"]))
    //{
        //$quote_box_text_value = $_POST["quote-box-text"];
    //}   
    //update_post_meta($post_id, "quote-box-text", $quote_box_text_value);    
}

add_action("save_post", "save_custom_meta_box", 10, 3);
?>