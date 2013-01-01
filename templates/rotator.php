<?php //Setup info
$rotator_location = array();
if (!empty($location)) {
  $rotator_location = array(
    array(
      'taxonomy' => 'base_rotator_location',
      'field'    => 'slug',
      'terms'    => $location
    )
  );
}

$rotator_args = array(
  'posts_per_page' => -1,
  'post_type'      => 'base_rotator',
  'tax_query'      => $rotator_location
);
$rotator_query = new WP_Query($rotator_args);
if($rotator_query->have_posts()){
	//pre-loop stuffs
		$i = 0;
		//collect captions
		$captions='';
	?>
	<div class="row">
		<div id="rotator-<?php echo $location; ?>" class="rotator">
			<?php while ($rotator_query->have_posts()) : $rotator_query->the_post(); ?>
				<?php $i++; ?>
				<?php if (has_post_thumbnail()) {
					if(has_excerpt()) {
						$captions .= '<span class="orbit-caption" id="caption' . $i . '">' . get_the_excerpt() . '</span>'; ?>
						<div data-caption="#caption<?php echo $i; ?>">
					<?php }else { ?>
						<div class="center">
					<?php }
							the_post_thumbnail('full'); ?>
						</div>
				<?php }else { ?>
					<div>
						<h3><?php the_title(); ?></h3>
						<?php the_excerpt(); ?>
					</div>
				<?php } ?>
		  <?php endwhile; ?>
		</div>
		<?php if($captions != '') {
			echo $captions;
		} ?>
	</div>
	<script type="text/javascript">
	   $(window).load(function() {
	       $(".rotator").orbit({
	         animation: 'fade',                  // fade, horizontal-slide, vertical-slide, horizontal-push
	         animationSpeed: 800,                // how fast animtions are
	         timer: true,                        // true or false to have the timer
	         resetTimerOnClick: false,           // true resets the timer instead of pausing slideshow progress
	         advanceSpeed: 3200,                 // if timer is enabled, time between transitions
	         pauseOnHover: true,                // if you hover pauses the slider
	         startClockOnMouseOut: true,        // if clock should start on MouseOut
	         startClockOnMouseOutAfter: 400,    // how long after MouseOut should the timer start again
	         directionalNav: true,               // manual advancing directional navs
	         captions: false,                     // do you want captions?
	         captionAnimation: 'fade',           // fade, slideOpen, none
	         captionAnimationSpeed: 400,         // if so how quickly should they animate in
	         bullets: false,                     // true or false to activate the bullet navigation
	         bulletThumbs: false,                // thumbnails for the bullets
	         bulletThumbLocation: '',            // location from this file where thumbs will be
	         afterSlideChange: function(){},     // empty function
	         fluid: 'fluid'                       // true or set a aspect ratio for content slides (ex: '4x3')
	       });
	   });
	</script>
	<?php wp_reset_postdata(); ?>
<?php } ?>