<form role="search" class="form-search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
	<label class="hide" for="s"><?php _e('Search for:', 'fin'); ?></label>
	<div class="input-group">
		<input class="span2 search-query" id="search"  type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" placeholder="<?php _e('Search', 'fin'); ?> <?php bloginfo('name'); ?>">
		<span class="input-group-btn">
			<input type="submit" class="btn" id="searchsubmit" value="<?php _e('Search', 'fin'); ?>" >
		</span>
	</div>
</form>