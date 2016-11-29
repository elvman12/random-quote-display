<?php
/*
Plugin Name: Random Quote Display
Plugin URI:  Not applicable
Description: Custom Post Type to record quotes and credit names.
Version:     1.04
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
		//'taxonomies' => array( 'category' ),
		'menu_icon' => 'dashicons-format-quote',
		'supports' => false // This line removes the default metaboxes for Title and Editor fields
	);
	
	register_post_type( 'quote', $args );
}

// Create Taxonomy for this custom post type
function rqd_taxonomy() {
	$labels = array(
		'name'                           => 'Quote Type',
		'singular_name'                  => 'Quote Types',
		'search_items'                   => 'Search Types',
		'all_items'                      => 'All Types',
		'edit_item'                      => 'Edit Type',
		'update_item'                    => 'Update Type',
		'add_new_item'                   => 'Add New Type',
		'new_item_name'                  => 'New Type Name',
		'menu_name'                      => 'Type',
		'view_item'                      => 'View Type',
		'popular_items'                  => 'Popular Type',
		'separate_items_with_commas'     => 'Separate Types with commas',
		'add_or_remove_items'            => 'Add or remove Types',
		'choose_from_most_used'          => 'Choose from the most used Types',
		'not_found'                      => 'No Types found'
		);
	
	register_taxonomy(
		'Type',
		'quote',
		array(
			'label' => 'Quote Type',
			'hierarchical' => true,
			'labels' => $labels,
			'public' => true,
			'show_admin_column' => true
		)
	);
}
add_action( 'init', 'rqd_taxonomy' );

// Plugin Activation
function ctd_flush_rewrites() {
	ctd_famous_quotes();
	flush_rewrite_rules();
	add_filter( 'widget_text', 'do_shortcode' );
}
register_activation_hook( __FILE__, 'ctd_flush_rewrites' );

// Plugin Deactivation
function ctd_flush_rewrites_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ctd_flush_rewrites_deactivate' );

// Add a custom sytlesheet to the plugin
function rqd_register_style(){
	wp_enqueue_style( 'rqd-style', plugins_url( 'rqd-style.css' , __FILE__ ) );
}
add_action('wp_enqueue_scripts','rqd_register_style');
add_action( 'admin_enqueue_scripts', 'rqd_register_style' );


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
    <input name="author-box-text" type="text" value="<?php echo "$author_box_text"; ?>"><br>
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
    <input name="quote-box-text" type="text" value="<?php echo "$quote_box_text"; ?>"><br>
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
function rqd_save_quote($post_id, $post, $update)
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
	
	global $wpdb;
	if ( get_post_type( $post_id ) == 'quote' ) {
		$quotetitle = get_post_meta($post_id, 'quote-box-text', true);
		$quoteauthor = get_post_meta($post_id, 'author-box-text', true);
		$title = $quotetitle . " &nbsp;&nbsp;(" . $quoteauthor . ")";
		$where = array( 'ID' => $post_id );
		$wpdb->update( $wpdb->posts, array( 'post_title' => $title ), $where );
	}			
}
add_action("save_post", "rqd_save_quote", 10, 3);

// Let's add a simple shortcode that can be used to add this to text widgets
function rqdshortcode () {
ob_start();
?>

<div class="rqd-container">
    	<?php
		$args=array('post_type'=>'quote', 'orderby'=>'rand', 'posts_per_page'=>'1');

		$randomquote=new WP_Query($args);
		while ($randomquote->have_posts()) : $randomquote->the_post();
			$ctd_newtitle = get_the_title();
			$ctd_newtitle = preg_replace("/\([^)]+\)/","",$ctd_newtitle);
			$ctd_newtitle = str_replace(" &nbsp;&nbsp;", '', $ctd_newtitle);
			
		
			?><p class="rqd-quote"><?php echo "\"" . $ctd_newtitle . "\"";?></p>
            
            <?php $authorname = get_post_meta( get_the_ID(), 'author-box-text', true ); ?>
            <p class="rqd-author"><?php echo "- " . $authorname . " -";?></p>
        <?php    
		endwhile;
		wp_reset_postdata();
		?> </div> <?php
		
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
add_shortcode( 'newshort', 'rqdshortcode' );

// Improvements for next IDP....

// Help text at the bottom of admin page.
// Manage columns for admin console, make them sortable
// Figure out how to add paramaters to shortcode so people can display a random quote of a certain type.
?>