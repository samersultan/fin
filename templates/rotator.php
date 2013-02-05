<?php //Setup info
global $rotator_loc;
$location = $rotator_loc;
$rotator_location = array();
if (!empty($location)) {
  $rotator_location = array(
    array(
      'taxonomy' => 'rotator_location',
      'field'    => 'slug',
      'terms'    => $location
    )
  );
}

$rotator_args = array(
  'posts_per_page' => -1,
  'post_type'      => 'rotator',
  'tax_query'      => $rotator_location
);

$rotator_query = new WP_Query($rotator_args);
if($rotator_query->have_posts()){
	// Add carousel js
	wp_enqueue_script('fin_carousel');
	
	// pre-loop stuffs
		$i = 0;
		// collect captions
		$captions='';
	?>
	<div id="rotator-<?php echo $location; ?>" class="carousel slide">
		<div class="carousel-inner">
			<?php while ($rotator_query->have_posts()) : $rotator_query->the_post(); ?>
				<div class="item">
					<?php if (has_post_thumbnail()) {
						the_post_thumbnail('full'); ?>
						<?php if(has_excerpt()) { ?>
							<div class="carousel-caption">
								<?php the_excerpt(); ?>
							</div>
						<? }
					}else { ?>
						<h3><?php the_title(); ?></h3>
						<?php the_excerpt(); ?>
					<?php } ?>
				</div>
			<?php endwhile; ?>
		</div>
		<a class="carousel-control left" href="#rotator-<?php echo $location; ?>" data-slide="prev"><i class="control icon-angle-left icon-2x"></i></a>
		<a class="carousel-control right" href="#rotator-<?php echo $location; ?>" data-slide="next"><i class="control icon-angle-right icon-2x"></i></a>
	</div>
	<?php wp_reset_postdata(); ?>
<?php } ?>