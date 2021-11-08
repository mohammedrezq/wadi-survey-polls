<?php

/**
 * Single Post type for Poll
 */
get_header();

// $the_post_id =  get_the_ID();
// $the_current_user_id = get_current_user_id();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>



	<div class="entry-content">


    <h1>TEST Wadi Poll</h1>
		
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->


<?php

get_footer();
