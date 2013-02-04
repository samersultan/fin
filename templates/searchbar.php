<form class="navbar-form form-search" role="search" method="get" id="searchbar" action="<?php echo home_url('/'); ?>">
	<label class="hide" for="s"><?php _e('Search for:', 'fin'); ?></label>
	<div class="input-group">
		<input id="search" class="search-query" type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" placeholder="<?php _e('Search', 'fin'); ?>">
		<span class="input-group-btn" style="width: auto;">
			<button class="btn"id="searchsubmit">
				<span class="hide"><?php _e('Search', 'fin'); ?></span><i class="icon-search"></i>
			</button>
		</span>
	</div>
</form>