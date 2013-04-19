<?php 
if(have_comments()) { ?>
  <ol class="comment-list reviews">
    <?php wp_list_comments(array('walker' => new Fin_Walker_Review)); ?>
  </ol>

  <?php /* Comment Pagination */
  if(get_comment_pages_count() > 1 && get_option('page_comments')) { // are there comments to navigate through ?>
    <nav id="comments-nav" class="pager">
      <ul class="pager">
        <?php if (get_previous_comments_link()) { ?>
          <li class="previous"><?php previous_comments_link(__('&larr; Older comments', 'fin')); ?></li>
        <?php }else{ ?>
          <li class="previous disabled"><a><?php _e('&larr; Older comments', 'fin'); ?></a></li>
        <?php } ?>
        <?php if (get_next_comments_link()) { ?>
          <li class="next"><?php next_comments_link(__('Newer comments &rarr;', 'fin')); ?></li>
        <?php }else{ ?>
          <li class="next disabled"><a><?php _e('Newer comments &rarr;', 'fin'); ?></a></li>
        <?php } ?>
      </ul>
    </nav>
  <?php } ?>

  <?php if(!comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments') && $comments_warning) { ?>
    <div class="alert-box secondary">
      <button type="button" class="close">&times;</button>
      <p><?php _e('Comments are closed.', 'fin'); ?></p>
    </div>
	<?php } ?>
<?php } ?>

<?php if(!have_comments() && !comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments') && $comments_warning) { ?>
  <div class="alert-box secondary">
    <button type="button" class="close">&times;</button>
    <p><?php _e('Comments are closed.', 'fin'); ?></p>
  </div>
<?php } ?>