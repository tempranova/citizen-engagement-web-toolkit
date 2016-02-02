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
      <?php 
        // gets comments
        /*
        $args_get = array(
          'order' => 'DESC',
          'post_id' => get_the_ID(),
        );
        $comments = get_comments( $args_get );

        $args_list = ['style' => 'ol', 'short_ping' => true, 'callback' => 'bcgov_comments'];

        wp_list_comments( $args_list, $comments );
        */
        // ORIGINAL CALL
        // wp_list_comments($args_list);
      ?>

<?php 
// gets comments
$args_get = array(
  'post_id' => get_the_ID(),
);

$comments = get_comments( $args_get );

class Walker_Simple_Example extends Walker {
  private $root_id;
  private $comments_root_replies = array();
  private $comments_root_recent = array();

  // Set the properties of the element which give the ID of the current item and its parent
  var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );

  // Displays end of a level. E.g '</ul>'
  // @see Walker::end_lvl()
  function end_lvl(&$output, $depth=0, $args=array()) {
    $output = array($this->comments_root_replies, $this->comments_root_recent);
  }

  // Displays start of an element. E.g '<li> Item Name'
  // @see Walker::start_el()
  function start_el(&$output, $item, $depth=0, $args=array()) {
    // top level root
    if ($item->comment_parent == 0) {
      $this->root_id = $item->comment_ID;
      $this->comments_root_replies[$this->root_id] = 0;
      $this->comments_root_recent[$this->root_id] = $item->comment_date_gmt;
    } else {
      // increment replies count
      $this->comments_root_replies[$this->root_id] = $this->comments_root_replies[$this->root_id] + 1;

      // check date
      $d_old = new DateTime($this->comments_root_recent[$this->root_id]);
      $d_new = new DateTime($item->comment_date_gmt);

      if ($d_new > $d_old) {
        $this->comments_root_recent[$this->root_id] = $item->comment_date_gmt;
      };
    }
  }
}
// returns array[0]= reply count, array[1]=date stamps
$walk = new Walker_Simple_Example();
$walkOutput = $walk->walk( $comments, 0 );

$walkOutputRepliesAsc = $walkOutput[0];
asort($walkOutputRepliesAsc);

$walkOutputRepliesDesc = $walkOutput[0];
arsort($walkOutputRepliesDesc);

$walkOutputDateAsc = $walkOutput[1];
asort($walkOutputDateAsc);

$walkOutputDateDesc = $walkOutput[1];
arsort($walkOutputDateDesc);

// build an 'index' array of the original comments
$commentRef = array();

foreach ($comments as $comment) {
  $commentRef[$comment->comment_ID] = $comment;
}
echo "walkOutputRepliesAsc\n\n\n";
print_r($walkOutputRepliesAsc);
echo "\n\n\n";
echo "walkOutputRepliesDesc\n\n\n";
print_r($walkOutputRepliesDesc);

/*
NOTE: 
walkOutputRepliesDesc is being formatted like so:
Array
(
    [12] => 3
    [14] => 3
    [21] => 2
    [24] => 1
    [20] => 0
    [22] => 0
    [23] => 0
    [30] => 0
    [26] => 0
    [25] => 0
    [31] => 0
)

But when wp_list_comments is processing arr_merge it's coming out inverted!


Could be an issue with unstable sorting functions:

'As the manual says, "If two members compare as equal, their order in the sorted array is undefined." 
This means that the sort used is not "stable" and may change the order of elements that compare equal.'

http://stackoverflow.com/a/4353844

http://stackoverflow.com/a/12678577
*/

$start_section = array();
$end_section = array();

// hack to flip the start_section
echo "\n\n\n";
echo "asort walkOutputRepliesDesc\n\n\n";
asort($walkOutputRepliesDesc);
print_r($walkOutputRepliesDesc);

foreach ($walkOutputRepliesDesc as $key => $val) {
  $start_section[] = $commentRef[$key];
}

foreach ($comments as $comment) {
   if ($comment->comment_parent != 0) {
     $end_section[] = $comment;
   }
}

$arr_merge = array_merge($start_section, $end_section);

$args_list = ['style' => 'ol', 'short_ping' => true, 'callback' => 'bcgov_comments'];
wp_list_comments( $args_list, $arr_merge );
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
