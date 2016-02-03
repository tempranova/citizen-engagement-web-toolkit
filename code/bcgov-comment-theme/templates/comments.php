<?php
if (post_password_required()) {
  return;
}
?>

<section id="comments" class="comments">

  <?php comment_form(); ?>

  
  <?php if (have_comments()) : ?>
    <h3><?php printf(_nx('One response to &ldquo;%2$s&rdquo;', '%1$s responses to &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'sage'), number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>'); ?></h3>

    <ol class="comment-list">
      <?php wp_list_comments(['style' => 'ol', 'short_ping' => true, 'callback' => 'bcgov_comments']);
      ?>
    </ol>
    <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
      <nav>
        <ul class="pager">
          <?php if (get_previous_comments_link()) : ?>
            <li class="previous"><?php previous_comments_link(__('&larr; Older comments', 'sage')); ?></li>
          <?php endif; ?>
          <?php if (get_next_comments_link()) : ?>
            <li class="next"><?php next_comments_link(__('Newer comments &rarr;', 'sage')); ?></li>
          <?php endif; ?>
        </ul>
      </nav>
    <?php endif; ?>


<?php 
/*
  (
      [comment_ID] => 25
      [comment_post_ID] => 4
      [comment_author] => @coak
      [comment_author_email] => web@oakenfold.ca
      [comment_author_url] => 
      [comment_author_IP] => 127.0.0.1
      [comment_date] => 2016-01-27 00:17:50
      [comment_date_gmt] => 2016-01-27 00:17:50
      [comment_content] => jkn
      [comment_karma] => 0
      [comment_approved] => 1
      [comment_agent] => Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36
      [comment_type] => 
      [comment_parent] => 0
      [user_id] => 1
  )

 $zed ===
       Array
 (
     [0] => Array
         (
             [31] => 0
             [30] => 0
             [26] => 0
             [25] => 0
             [24] => 1
             [23] => 0
             [22] => 0
             [21] => 2
             [20] => 0
             [14] => 3
             [12] => 3
         )
 
     [1] => Array
         (
             [31] => 2016-01-31 23:41:45
             [30] => 2016-01-29 06:04:49
             [26] => 2016-01-27 00:17:53
             [25] => 2016-01-27 00:17:50
             [24] => 2016-01-27 00:25:55
             [23] => 2016-01-27 00:17:42
             [22] => 2016-01-27 00:17:39
             [21] => 2016-01-27 00:18:08
             [20] => 2016-01-27 00:17:20
             [14] => 2016-01-27 00:02:48
             [12] => 2016-01-27 00:02:35
         )
 
 )
*/
?>

  <?php endif; // have_comments() ?>

  <?php if (!comments_open() && get_comments_number() != '0' && post_type_supports(get_post_type(), 'comments')) : ?>
    <div class="alert alert-warning">
      <?php _e('Comments are closed.', 'sage'); ?>
    </div>
  <?php endif; ?>

  
</section>
