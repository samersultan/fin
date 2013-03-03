<form class="form-search row collapse input-group round" role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
	<div class="small-11 columns">
		<input id="search" class="search-query" type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" placeholder="<?php _e('Search', 'fin'); ?>">
	</div>
	<div class="small-1 columns">
		<button class="button postfix" id="searchsubmit">
			<i class="icon-search"></i><span class="hide"><?php _(' search'); ?></span>
		</button>
	</div>
</form>