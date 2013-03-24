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
	// Get Slideshow Options
	$options = get_option('fin_theme_options');
	$dataOptions = '';
	$timerSpeed = $options['orbit_timer_speed'];
	$animationSpeed = $options['orbit_animation_speed'];
	$bullets = var_export($options['orbit_bullets'], true);
	if($timerSpeed != '' || $animationSpeed != '' || $bullets != '') {
		$dataOptions .= ' data-options="';
		if($timerSpeed != '') {
			$dataOptions .= 'timer_speed:' . $timerSpeed . ';';
		}
		if($animationSpeed != '') {
			$dataOptions .= 'animation_speed:' . $animationSpeed . ';';
		}
		if($bullets != '') {
			$dataOptions .= 'bullets:' . $bullets . ';';
		}
		$dataOptions .= '"';
	}	?>
	<ul data-orbit class="orbit"<?php echo $dataOptions; ?>>
		<?php while ($rotator_query->have_posts()) : $rotator_query->the_post(); ?>
			<li>
				<?php if (has_post_thumbnail()) {
					the_post_thumbnail('full');
					if(has_excerpt()) { ?>
						<div class="orbit-caption"><?php the_excerpt(); ?></div>
					<?php }
				}else { ?>
					<h3><?php the_title(); ?></h3>
					<?php the_excerpt();
				} ?>
			</li>
		<?php endwhile; ?>
	</ul>
	<?php wp_reset_postdata();
} ?>