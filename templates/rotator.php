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
	// enqueue orbit.js
	wp_enqueue_script('fin_orbit_js');
	
	// pre-loop stuffs
		$i = 0;
		// collect captions
		$captions='';
	?>
	<div id="rotator-<?php echo $location; ?>" class="rotator">
		<?php while ($rotator_query->have_posts()) : $rotator_query->the_post(); ?>
			<?php $i++; ?>
			<?php if (has_post_thumbnail()) {
				if(has_excerpt()) {
					$captions .= '<span class="orbit-caption" id="caption' . $i . '">' . get_the_excerpt() . '</span>'; ?>
					<div data-caption="#caption<?php echo $i; ?>">
				<?php }else { ?>
					<div>
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
	<?php wp_reset_postdata(); ?>
<?php } ?>