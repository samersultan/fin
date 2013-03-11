<?php
/*
Template Name: Sitemap
*/
?>

<?php get_template_part('templates/page', 'header'); ?>
<section <?php post_class('single'); ?>>
	<header>
		<h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Bookmark for <?php the_title_attribute(); ?>" class="bookmark"><?php the_title(); ?></a></h3>
	</header>
	<section class="entry-content">
		<?php the_content(); ?>
		<div id="sitemap-pages">
			<h5><?php _e('Pages', 'fin'); ?></h5>
			<ul>
				<?php wp_list_pages('depth=0&sort_column=menu_order&title_li='); ?>
			</ul>
		</div>
		<div id="sitemap-articles">
			<h5><?php _e('Articles', 'fin'); ?></h5>
			<?php $cats = get_categories();
			foreach($cats as $cat) {
				$cat_args = array(
				  'posts_per_page' => -1,
				  'cat' => $cat->cat_ID
				);
				$cat_query = new WP_Query($cat_args); ?>
				<?php if($cat_query->have_posts()) { ?>
					<h6><a href="<?php echo get_category_link($cat->term_id);?>"><?php echo $cat->cat_name; ?></a></h6>
					<ul>
						<?php while($cat_query->have_posts()): $cat_query->the_post(); ?>
							<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
						<?php endwhile; ?>
					</ul>
				<?php }
			} ?>
		</div>
		<?php $taxonomy = get_terms('post_tag');
		if($taxonomy) { ?>
			<div id="sitemap-tags">
				<h5>Tags</h5>
				<ul>
					<?php foreach ($taxonomy as $term) { ?>
						<li><a href="<?php echo esc_attr(get_term_link($term, 'post_tag')); ?>"><?php echo $term->name; ?> <small>(<?php echo $term->count; ?>)</small></a></li>
					<?php } ?>
				</ul>
			</div>
		<?php } ?>
	</section>
</section>