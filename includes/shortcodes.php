<?php

// Shortcodes used in this plugin

function rqdshortcode () {
ob_start();
?>

<div class="rqd-container">
    	<?php
		$args=array('post_type'=>'quote', 'orderby'=>'rand', 'posts_per_page'=>'1');

		$randomquote=new WP_Query($args);
		while ($randomquote->have_posts()) : $randomquote->the_post();
			$ctd_newtitle = get_the_title();			
		
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
add_shortcode( 'quickquote', 'rqdshortcode' );
add_filter( 'widget_text', 'do_shortcode' );