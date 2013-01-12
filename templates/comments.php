<?php $options = get_option('fin_theme_options');
$comments_warning = $options['comments_warning'];
if(post_password_required() && $comments_warning) { ?>
  <section id="comments">
	    <div class="alert-box secondary">
	      <a href="" class="close">&times;</a>
	      <p><?php _e('This area is password protected. Enter the password to view comments.', 'fin'); ?></p>
    	</div>
  </section><!-- /#comments -->
<?php } ?>

<?php if(have_comments()) { ?>
  <section id="comments">
    <h5><?php printf(_n('One Response to &ldquo;%2$s&rdquo;', '%1$s Responses to &ldquo;%2$s&rdquo;', get_comments_number(), 'fin'), number_format_i18n(get_comments_number()), get_the_title()); ?></h5>

    <ol class="commentlist">
      <?php wp_list_comments(array('callback' => 'fin_comment')); ?>
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
      <div class="alert-box secondary">
        <a href="" class="close">&times;</a>
        <p><?php _e('Comments are closed.', 'fin'); ?></p>
      </div>
   <?php } ?>
  </section><!-- /#comments -->
<?php } ?>

<?php if(!have_comments() && !comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments') && $comments_warning) { ?>
  <section id="comments">
    <div class="alert-box secondary">
      <a href="" class="close">&times;</a>
      <p><?php _e('Comments are closed.', 'fin'); ?></p>
    </div>
  </section><!-- /#comments -->
<?php } ?>

<?php if (comments_open()) { ?>
	<?php comment_form(); ?>
<?php } ?>