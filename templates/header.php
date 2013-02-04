<header role="banner">
	<hgroup>
		<h1 class="brand"><a href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a></h1>
		<?php if(get_bloginfo('description') != '') { ?>
			<h2 class="description"><a href="<?php echo esc_url(get_permalink(get_page_by_path( 'about'))); ?>"><?php echo get_bloginfo('description'); ?></a></h2>
		<?php } ?>
	</hgroup>
</header>
<?php if (has_nav_menu('primary_menu')) { ?>
	<div class="navbar sticky" role="navigation">
	  <div class="navbar-inner">
	    <div class="container">
	      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	        <b class="caret"></b>
	      </a>
	      <a class="brand visible-phone" href="<?php echo home_url(); ?>/">
	        <?php bloginfo('name'); ?>
	      </a>
	      <nav class="nav-main nav-collapse collapse">
	        <?php wp_nav_menu(array('theme_location' => 'primary_menu', 'menu_class' => 'nav')); ?>
	      </nav>
	    </div>
	  </div>
	</div>
<?php } ?>