<form role="search" method="get" id="searchbar" action="<?php echo home_url('/'); ?>">
	<label class="hide" for="s"><?php _e('Search for:', 'fin'); ?></label>
	<input id="search" class="search-query" type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" placeholder="<?php _e('Search', 'fin'); ?> <?php bloginfo('name'); ?>">
	<input type="submit" id="searchsubmit" value="<?php _e('Search', 'fin'); ?>" class="hide">
</form>