<?php if(is_single() || is_page()) {
	$prev = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
	$next = get_adjacent_post( false, '', false );
	if($prev || $next) { ?>
		<nav id="pagination" class="row" role="navigation">
			<?php if($next) {
				next_post_link('%link', '&laquo; Newer', true);
			}
			if($prev) {
				previous_post_link('%link','Older &raquo;', true);
			} ?>
		</nav>
	<?php } ?>
<?php }else {
	if ($wp_query->max_num_pages > 1) {
		$prev = get_previous_posts_link();
		$next = get_next_posts_link();
		if($prev || $next) ?>
		<nav id="pagination" class="row" role="navigation">
			<?php if($prev) {
				previous_posts_link('&laquo; Newer');
			}
			if($next) {
				next_posts_link('Older &raquo;');
			} ?>
		</nav>
	<?php }
} ?>