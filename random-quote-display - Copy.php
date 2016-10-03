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
		'rewrite' => array( 'slug' => 'quote' ),
		'menu_position' => 5,
		'show_ui' => true,
		'show_in_admin_bar' => true,
		'menu_icon' => 'dashicons-format-quote',
		'supports' => array ( 'title' )
	);
	
	register_post_type( 'quote', $args );
}
add_action( 'init', 'ctd_famous_quotes' );

// Change the place holder text for entering a new famous quote
function ctd_change_title_text( $title ){
     $screen = get_current_screen();
 
     if  ( 'quote' == $screen->post_type ) {
          $title = 'Enter a Famous Quote';
     }
 
     return $title;
}
 
add_filter( 'enter_title_here', 'ctd_change_title_text' );

// Add Custom Meta Box

// MetaBox Markup, HTML, etc.
function ctd_quote_author_meta_markup() {
	
    wp_nonce_field(basename(__FILE__), "ctd-quote-nonce");

    ?>
    	<style>
			h2.ui-sortable-handle span {
				font-size: 20px;
				font-weight: 400;
				margin: 0;
				padding: 9px 15px 4px 0;
				line-height: 29px;
			}
		</style>
        
    	<div>
            <label for="author-credit">Quote Credit</label>
            <input name="author-credit" type="text" value="<?php echo get_post_meta($object->ID, "author-credit", true); ?>">
        </div>    
<?php            
}

// Meta Box - this adds the meta box itself
function ctd_quote_author_metabox() {
    add_meta_box("demo-meta-box", "Quote Author", "ctd_quote_author_meta_markup", "quote", "normal", "high", null);
}

add_action("add_meta_boxes", "ctd_quote_author_metabox");

// Meta Box - Save the input to dbase so we can use it and display it
function ctd_save_custom_quota_meta($post_id, $quote, $update)
{
    if (!isset($_POST["ctd-quote-nonce"]) || !wp_verify_nonce($_POST["ctd-quote-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "quote";
    if($slug != $post->post_type)
        return $post_id;

    $meta_box_text_value = "";
    //$meta_box_dropdown_value = "";
    //$meta_box_checkbox_value = "";

    if(isset($_POST["author-credit"]))
    {
        $meta_box_text_value = $_POST["author-credit"];
    }   
    update_post_meta($post_id, "author-credit", $meta_box_text_value);

    //if(isset($_POST["meta-box-dropdown"]))
    //{
        //$meta_box_dropdown_value = $_POST["meta-box-dropdown"];
    //}   
    //update_post_meta($post_id, "meta-box-dropdown", $meta_box_dropdown_value);

    //if(isset($_POST["meta-box-checkbox"]))
    //{
        //$meta_box_checkbox_value = $_POST["meta-box-checkbox"];
    //}   
    //update_post_meta($post_id, "meta-box-checkbox", $meta_box_checkbox_value);
}

add_action("save_post", "ctd_save_custom_quota_meta", 10, 3);
?>