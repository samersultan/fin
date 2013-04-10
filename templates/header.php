<?php do_action('get_header'); ?>
<header id="header" role="banner" <?php if(!is_front_page()) { echo 'class="hide-for-small"'; } ?>>
	<hgroup>
		<?php $options = get_option('fin_theme_options');
		$logo = $options['logo'];
		if($logo) { ?>
			<figure class="logo" role="logo"><a href="<?php echo home_url(); ?>/"><img src="<?php echo $logo; ?>"></a></figure>
		<?php } ?>
		<h1 class="brand"><a href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a></h1>
		<?php if(get_bloginfo('description') != '') { ?>
			<h2 class="description"><a href="<?php echo esc_url(get_permalink(get_page_by_path( 'about'))); ?>"><?php echo get_bloginfo('description'); ?></a></h2>
		<?php } ?>
	</hgroup>
</header>
<?php if (has_nav_menu('primary_menu')) { ?>
	<div class="sticky">
		<nav id="primary_navigation" class="top-bar" role="navigation">
			<ul class="title-area">
		    <li class="name">
	        <h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
		    </li>
		    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
			</ul>
			<section class="top-bar-section">
				<?php wp_nav_menu(array('theme_location' => 'primary_menu', 'menu_class' => 'right')); ?>
			</section>
		</nav>
	</div>
<?php } ?>