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
	wp_enqueue_script('fin_orbit');
	
	// pre-loop stuffs
		$i = 0;
		// collect captions
		$captions='';
	?>
	<ul data-orbit class="orbit">
		<?php while ($rotator_query->have_posts()) : $rotator_query->the_post(); ?>
			<li>
				<?php if (has_post_thumbnail()) {
					the_post_thumbnail('full');
					if(has_excerpt()) { ?>
						<div class="orbit-caption"><?php the_excerpt(); ?></div>
					<?php } ?>
				<?php }else { ?>
					<h3><?php the_title(); ?></h3>
					<?php the_excerpt(); ?>
				<?php } ?>
			</li>
		<?php endwhile; ?>
	</ul>
	<?php wp_reset_postdata(); ?>
<?php } ?>