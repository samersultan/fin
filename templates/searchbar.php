<form class="navbar-form form-search row collapse input-group round" role="search" method="get" id="searchbar" action="<?php echo home_url('/'); ?>">
	<div class="small-10 columns">
		<label class="hide" for="s"><span class="hide"><?php _(' search'); ?></span></label>
		<input id="search" class="search-query radius" type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" placeholder="<?php _e('Search', 'fin'); ?>">
	</div>
	<div class="small-2 columns">
		<button class="button postfix" id="searchsubmit">
			<i class="icon-search"></i><span class="hide"><?php _(' search'); ?></span>
		</button>
	</div>
</form>