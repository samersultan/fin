<?php //Setup info
global $slideshow_loc;
$location = $slideshow_loc;
$slideshow_location = array();
if (!empty($location)) {
  $slideshow_location = array(
    array(
      'taxonomy' => 'slideshow_location',
      'field'    => 'slug',
      'terms'    => $location
    )
  );
}

$slideshow_args = array(
  'posts_per_page' => -1,
  'post_type'      => 'slideshow',
  'tax_query'      => $slideshow_location,
  'order'          => 'ASC',
);

$slideshow_query = new WP_Query($slideshow_args);
if($slideshow_query->have_posts()){
	// Get Slideshow Options
	$options = get_option('fin_theme_options');
	$timerSpeed = $options['orbit_timer_speed'];
	$animationSpeed = $options['orbit_animation_speed'];
	$bullets = var_export($options['orbit_bullets'], true);
	$dataOptions = '';
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
		<?php while ($slideshow_query->have_posts()) : $slideshow_query->the_post(); ?>
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