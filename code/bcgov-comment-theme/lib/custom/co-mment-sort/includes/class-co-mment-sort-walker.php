<?php 

class Walker_Co_Mment_Sort extends Walker {
  private $root_id;
  private $comments_root_replies = array();
  private $comments_root_recent = array();

  // Set the properties of the element which give the ID of the current item and its parent
  var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );

  // Displays end of a level. E.g '</ul>'
  // @see Walker::end_lvl()
  //function end_lvl(&$output, $depth=0, $args=array()) {
    // it looks like end_lvl isn't consistently called for some reason
    // using end_el instead
  //}

  // Displays start of an element. E.g '<li> Item Name'
  // @see Walker::start_el()
  function start_el(&$output, $item, $depth=0, $args=array(), $current_object_id=0) {
    // top level root
    if ($item->comment_parent == 0) {

      $this->root_id = $item->comment_ID;
      $this->comments_root_replies[$this->root_id] = 0;
      $this->comments_root_recent[$this->root_id] = $item->comment_date_gmt;

    } else {
      // increment replies count
      $this->comments_root_replies[$this->root_id] = $this->comments_root_replies[$this->root_id] + 1;

      // check date of children
      // commented out due to requirement to sort by parent date
      // $d_old = new DateTime($this->comments_root_recent[$this->root_id]);
      // $d_new = new DateTime($item->comment_date_gmt);

      // if ($d_new > $d_old) {
      //   $this->comments_root_recent[$this->root_id] = $item->comment_date_gmt;
      // };
    }
  }

  function end_el( &$output, $item, $depth = 0, $args = array()) {
    $output = array($this->comments_root_replies, $this->comments_root_recent);
  }
}
