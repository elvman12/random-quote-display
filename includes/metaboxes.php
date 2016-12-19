<?php

// Markup and Code for Displaying Meta Boxes


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
//add_action('do_meta_boxes','ctd_remove_meta_stuff');