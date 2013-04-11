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
		<?php the_content(); ?>
		<div class="sitemap-type" id="sitemap-pages">
			<h4><?php _e('Pages', 'fin'); ?></h4>
			<ul>
				<?php wp_list_pages('depth=0&sort_column=menu_order&title_li='); ?>
			</ul>
		</div>
		<div class="sitemap-type" id="sitemap-articles">
			<h4><?php _e('Articles', 'fin'); ?></h4>
			<?php $cats = get_categories();
			foreach($cats as $cat) {
				$cat_args = array(
				  'posts_per_page' => -1,
				  'cat' => $cat->cat_ID
				);
				$cat_query = new WP_Query($cat_args); ?>
				<?php if($cat_query->have_posts()) { ?>
					<div class="sitemap-sub">
						<h5><a href="<?php echo get_category_link($cat->term_id);?>"><?php echo $cat->cat_name; ?></a></h5>
						<ul>
							<?php while($cat_query->have_posts()): $cat_query->the_post(); ?>
								<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
							<?php endwhile; ?>
						</ul>
					</div>
				<?php }
			} ?>
		</div>
		<?php $taxonomy = get_terms('post_tag');
		if($taxonomy) { ?>
			<div class="sitemap-type" id="sitemap-tags">
				<h4>Tags</h4>
				<ul>
					<?php foreach ($taxonomy as $term) { ?>
						<li><a href="<?php echo esc_attr(get_term_link($term, 'post_tag')); ?>"><?php echo $term->name; ?> <small>(<?php echo $term->count; ?>)</small></a></li>
					<?php } ?>
				</ul>
			</div>
		<?php } ?>
		<?php // All Post Types
		$post_types = get_post_types(array('publicly_queryable'=>true, '_builtin'=>false), 'objects');
		// get all extra public post types
		foreach($post_types as $post_type) {
			if($post_type->name != 'post' ) {
			$labels = $post_type->labels; ?>
				<div class="sitemap-type" id="sitemap-<?php echo $post_type->name; ?>">
					<h4><?php _e($labels->name, 'fin'); ?></a></h4>
					<?php // get type of taxonomy (category, tags etc.)
					$taxonomies = get_object_taxonomies($post_type->name, 'objects');
					foreach($taxonomies as $taxonomy) {
						if($taxonomy->hierarchical == true && $taxonomy->public == true && $taxonomy->query_var != false) {
							$tax_labels = $taxonomy->labels; ?>
							<?php // get taxonomy terms
							$terms = get_terms($taxonomy->name);
							foreach ($terms as $term) {
								$tax_args = array(
								  'posts_per_page' => -1,
								  $taxonomy->name => $term->slug
								);
								// Query based on taxonomy
								$tax_query = new WP_Query( $tax_args );
								if($tax_query->have_posts()) { ?>
									<div class="sitemap-sub">
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
					} ?>
				</div>
			<?php }
		} ?>
	</section>
</section>