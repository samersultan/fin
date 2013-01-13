<header id="header">
	<?php if (has_nav_menu('primary_menu')) { ?>
		<nav id="primary_navigation" class="top-bar" role="navigation">
			<ul>
	      <li class="name show-for-small">
	          <h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
	      </li>          
	      	<li class="toggle-topbar"><a href="#"></a></li>
			</ul>
	  	<section>
				<?php wp_nav_menu(array('theme_location' => 'primary_menu', 'menu_class' => 'right')); ?>
			</section>
		</nav>
	<?php } ?>
	<hgroup>
		<h1 class="brand"><a href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a></h1>
		<h2 class="description"><?php echo get_bloginfo('description'); ?></h2>
	</hgroup>
</header>