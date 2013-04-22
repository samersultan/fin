<?php
/*
Template Name: Sitemap
*/
?>

<section <?php post_class('single'); ?>>
	<header>
		<h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Bookmark for <?php the_title_attribute(); ?>" class="bookmark"><?php the_title(); ?></a></h3>
	</header>
	<section class="entry-content">
		<div class="sitemap-section">
			<h5><?php _e('Pages', 'fin'); ?></h5>
			<ul>
				<?php wp_list_pages('depth=0&sort_column=menu_order&title_li='); ?>			
			</ul>
		</div>
		<?php // Loop through all Post Types
		$post_types = get_post_types(array('public'=>true), 'objects');
		foreach ($post_types as $post_type) {
			// don't output pages twice
			if($post_type->name == 'page') {
				continue;
			}
			$labels = $post_type->labels;
			// Use hierarchical taxonomies to organize if available
			$taxonomies = get_object_taxonomies($post_type->name, 'objects');
			$tax_check = false; // set to true when a taxonomy is found.
			foreach ($taxonomies as $taxonomy) {
				if($taxonomy->hierarchical == true && $taxonomy->public == true && $taxonomy->query_var != false) {
					// Get Taxonomy Terms
					$terms = get_terms($taxonomy->name);
					foreach ($terms as $term) {
						$tax_args = array(
						  'posts_per_page' => -1,
						  'post_type' => $post_type->name,
						  $taxonomy->name => $term->slug,
						);
						$tax_query = new WP_Query( $tax_args );
						if($tax_query->have_posts()) {
							$tax_check = true; ?>
							<div class="sitemap-section">
								<h5><a href=""><?php echo $term->name; ?></a></h5>
								<ul>
									<?php while($tax_query->have_posts()): $tax_query->the_post(); ?>
										<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
									<?php endwhile; ?>
								</ul>
							</div>
						<?php }
					}
				}
			}
			if( $tax_check == false ) {
				$type_args = array(
				  'posts_per_page' => -1,
				  'post_type' => $post_type->name
				);
				$type_query = new WP_Query( $type_args );
				if($type_query->have_posts()) { ?>
					<div class="sitemap-section">
						<h5><?php _e($labels->name, 'fin'); ?></a></h5>
						<ul>
							<?php while($type_query->have_posts()): $type_query->the_post(); ?>
								<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
							<?php endwhile; ?>
						</ul>
					</div>
				<?php }
			}
		} ?>
	</section>
</section>