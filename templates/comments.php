<div class="row">
	<?php // Reply Form
	if (comments_open()) { ?>
		<div class="span6">
			<?php comment_form(); ?>
		</div>
	<?php }
	// Comments
	$options = get_option('fin_theme_options');
	$comments_warning = $options['comments_warning'];
	if(post_password_required() && $comments_warning) { ?>
	  <section id="comments span12">
		    <div class="alert alert-info">
		      <button type="button" class="close" data-dismiss="alert">&times;</button>
		      <p><?php _e('This area is password protected. Enter the password to view comments.', 'fin'); ?></p>
	    	</div>
	  </section>
	<?php } ?>
	
	<?php if(have_comments()) { ?>
	  <section id="comments" class="span6">
	    <h5><?php printf(_n('One Response to &ldquo;%2$s&rdquo;', '%1$s Responses to &ldquo;%2$s&rdquo;', get_comments_number(), 'fin'), number_format_i18n(get_comments_number()), get_the_title()); ?></h5>
	
	    <ol class="commentlist media-list">
	      <?php wp_list_comments(array('walker' => new Fin_Walker_Comment)); ?>
	    </ol>
	
	    <?php /* Pagination */
	    if(get_comment_pages_count() > 1 && get_option('page_comments')) { // are there comments to navigate through ?>
	      <nav id="comments-nav" class="pager">
	        <ul class="pager">
	          <?php if (get_previous_comments_link()) : ?>
	            <li class="previous"><?php previous_comments_link(__('&larr; Older comments', 'fin')); ?></li>
	          <?php else: ?>
	            <li class="previous disabled"><a><?php _e('&larr; Older comments', 'fin'); ?></a></li>
	          <?php endif; ?>
	          <?php if (get_next_comments_link()) : ?>
	            <li class="next"><?php next_comments_link(__('Newer comments &rarr;', 'fin')); ?></li>
	          <?php else: ?>
	            <li class="next disabled"><a><?php _e('Newer comments &rarr;', 'fin'); ?></a></li>
	          <?php endif; ?>
	        </ul>
	      </nav>
	    <?php } ?>
	
	    <?php if(!comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments') && $comments_warning) { ?>
	      <div class="alert alert-info">
	        <button type="button" class="close" data-dismiss="alert">&times;</button>
	        <p><?php _e('Comments are closed.', 'fin'); ?></p>
	      </div>
	   <?php } ?>
	  </section>
	<?php } ?>
	
	<?php if(!have_comments() && !comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments') && $comments_warning) { ?>
	  <section id="comments">
	    <div class="alert alert-info">
	      <button type="button" class="close" data-dismiss="alert">&times;</button>
	      <p><?php _e('Comments are closed.', 'fin'); ?></p>
	    </div>
	  </section>
	<?php } ?>
</div>