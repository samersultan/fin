<article <?php post_class('single container'); ?>>
	<header>
		<h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Bookmark for <?php the_title_attribute(); ?>" class="bookmark"><?php the_title(); ?></a></h3>
	</header>
	<section class="entry-content row">
		<?php // Display Videos
		 
		// Utility function - allow us to strpos an array
		if ( ! function_exists( 'video_strpos_arr' )) {
		    function video_strpos_arr($haystack, $needle) {
		 
		    if( !is_array($needle) ) $needle = array($needle);
		 
		        foreach( $needle as $what ) {
		            if( ($pos = strpos($haystack, $what) ) !==false ) return $pos;
		        }
		 
		        return false;
		    }
		}
		 
		// Get Ready Display the Video
		$embedCheck     = array("<embed", "<video", "<ifram");// only checking against the first 6
		$mykey_values   = get_post_custom_values('_format_audio_embed');
		$media_to_display = '';
		 
		// iterate over values passed
		foreach ( $mykey_values as $key => $value ) {
		     if ( !empty($value) ) {
		        $firstCar = substr($value, 0, 6); // get the first 6 char.
		 
		        // if its a http(s).
		        if ( strpos($firstCar, "http:/" ) !== false || strpos($firstCar, "https:" ) !== false ) {
		            // send it to wp_oembed to see if the link is oembed enabled.
		            (wp_oembed_get($value) !==false ?
		                $media_to_display = '<div class="video" style="width:100%; overflow:hidden;">' .
		            	wp_oembed_get($value) . '</div>' :
		            	// if not output a link.
		            	$media_to_display =  '<a class="button videolink" href="' .
		            	$value . '" target="_blank">Video link: ' . the_title() . '</a>'
		            );
		        }
		 
		        // if its the embed code that matches our array defined above.
		        else if ( video_strpos_arr($firstCar, $embedCheck ) !== false ) {
		        	$media_to_display = '<div class="video" style="width:100%; overflow:hidden;">' .$value. '</div>';
		 
		        }
		    }
		}; // end foreach
		echo apply_filters('the_content', $media_to_display );
		the_content(); ?>
	</section>
	<footer>
		<?php get_template_part('templates/content/meta'); ?>
	</footer>
</article>