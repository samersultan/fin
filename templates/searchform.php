<form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
	<div class="row collapse">
		<div class="six mobile-three columns">
			<label class="hide" for="s"><?php _e('Search for:', 'fin'); ?></label>
			<input id="search" class="search-query" type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" placeholder="<?php _e('Search', 'fin'); ?> <?php bloginfo('name'); ?>">
		</div>
		<div class="two mobile-one columns end">
			<input type="submit" id="searchsubmit" value="<?php _e('Search', 'fin'); ?>" class="postfix button expand radius">
		</div>
	</div>
</form>