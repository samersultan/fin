<form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
	<div class="row collapse">
		<div class="eight mobile-three columns">
			<label class="hide" for="s"><?php _e('Search for:', 'fin'); ?></label>
			<input id="s" class="search-query" type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" placeholder="<?php _e('Search', 'fin'); ?> <?php bloginfo('name'); ?>">
		</div>
		<div class="four mobile-one columns">
			<input type="submit" id="searchsubmit" value="<?php _e('Search', 'reverie'); ?>" class="postfix button">
		</div>
	</div>
</form>