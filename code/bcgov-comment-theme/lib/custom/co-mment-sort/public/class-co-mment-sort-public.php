<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://oakenfold.ca
 * @since      1.0.0
 *
 * @package    Co_Mment_Sort
 * @subpackage Co_Mment_Sort/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Co_Mment_Sort
 * @subpackage Co_Mment_Sort/public
 * @author     Your Name <email@example.com>
 */
class Co_Mment_Sort_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $co_mment_sort    The ID of this plugin.
	 */
	private $co_mment_sort;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $co_mment_sort       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $co_mment_sort, $version ) {

		$this->co_mment_sort = $co_mment_sort;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Co_Mment_Sort_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Co_Mment_Sort_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->co_mment_sort, plugin_dir_url( __FILE__ ) . 'css/co-mment-sort.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Co_Mment_Sort_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Co_Mment_Sort_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->co_mment_sort, plugin_dir_url( __FILE__ ) . 'js/co-mment-sort-public.js', array( 'jquery' ), $this->version, false );

	}

//  /**
//   * 
//   *
//   * @since     1.0.0
//   * @return    string    The version number of the plugin.
//   */
//  public function get_comment_sort() {
//    // gets comments
//    $args_get = array(
//      'post_id' => get_the_ID(),
//    );
//
//    $comments = get_comments( $args_get );
//
//    // returns array[0]= reply count, array[1]=date stamps
//    $walk = new Walker_Co_Mment_Sort();
//    $walkOutput = $walk->walk( $comments, 0 );
//
//    $walkOutputRepliesAsc = $walkOutput[0];
//    sasort($walkOutputRepliesAsc);
//
//    $walkOutputRepliesDesc = $walkOutput[0];
//    sarsort($walkOutputRepliesDesc);
//
//    $walkOutputDateAsc = $walkOutput[1];
//    sasort($walkOutputDateAsc);
//
//    $walkOutputDateDesc = $walkOutput[1];
//    sarsort($walkOutputDateDesc);
//
//    // $comment_root_sorted = $walkOutputRepliesAsc;
//    // $comment_root_sorted = $walkOutputRepliesDesc;
//     $comment_root_sorted = $walkOutputDateAsc;
//    // $comment_root_sorted = $walkOutputDateDesc;
//
//    $comments_sorted = $this->merge_comment_array($comments, $comment_root_sorted)
//
//    return $comments_sorted;
//  }


/*
generate sort ui using hook

use url params:
../index.php/2016/01/27/test/comment-page-2/#comments?cos=r&cod=d
cos
type: sort
r === replies
d === date

cod
tupe: direction
d === descending
a === ascending

update links in previous next to append sort
*/

  /**
   * 
   *
   * @since     1.0.0
   * @return    
   */
  public function get_comment_sort($comments) {
    
    $direction = true;
    if (isset($_GET['com_dir'])) {
      if ($_GET['com_dir'] == "asc") {
        $direction = false;
      }
    }

    if (isset($_GET['com_sort'])) {
        if ($_GET['com_sort'] == "replies") {
          $output = $this->get_comment_sort_replies($comments, $direction);
        } else {
          $output = $this->get_comment_sort_date($comments, $direction);
        }
    } else {
      $output = $this->get_comment_sort_date($comments, $direction);
    }

    return $output;
  }

  /**
   * 
   *
   * @since     1.0.0
   * @return    
   */
  public function get_comment_sort_date($comments, $direction_bool) {
    // returns array[0]= reply count, array[1]=date stamps
    $walk = new Walker_Co_Mment_Sort();

    $walkOutput = $walk->walk( $comments, 0 );
    $walkOutputDate = $walkOutput[1];

    if ($direction_bool == true) {
      sasort($walkOutputDate);
    } else {
      sarsort($walkOutputDate);
    }
    
    $comment_root_sorted = $walkOutputDate;
    $comments_sorted = $this->merge_comment_array($comments, $comment_root_sorted);
    return $comments_sorted;
  }

  /**
   * 
   *
   * @since     1.0.0
   * @return    array    Sorted comments array
   */
  public function get_comment_sort_replies($comments, $direction_bool) {
    // returns array[0]= reply count, array[1]=date stamps
    $walk = new Walker_Co_Mment_Sort();
    $walkOutput = $walk->walk( $comments, 0 );
    $walkOutputReplies = $walkOutput[0];

    if ($direction_bool == true) {
      sasort($walkOutputReplies);
    } else {
      sarsort($walkOutputReplies);
    }

    $comment_root_sorted = $walkOutputReplies;
    $comments_sorted = $this->merge_comment_array($comments, $comment_root_sorted);
    return $comments_sorted;
  }

  /**
   * 
   *
   * @since     1.0.0
   * @return    array    
   */
  public function merge_comment_array($comments, $comment_root_sorted) {
    // build an 'index' array of the original comments
    $commentRef = array();
    foreach ($comments as $comment) {
      $commentRef[$comment->comment_ID] = $comment;
    }

    // put the top levels first 
    $start_section = array();
    $end_section = array();

    foreach ($comment_root_sorted as $key => $val) {
      $start_section[] = $commentRef[$key];
    }
    // then the children
    foreach ($comments as $comment) {
       if ($comment->comment_parent != 0) {
         $end_section[] = $comment;
       }
    }
    // reunion
    $arr_merge = array_merge($start_section, $end_section);

    return $arr_merge;
  }

  /**
   * 
   *
   * @since     1.0.0
   * @return    array    
   */
  public function comments_pagenum_link($content) {
    

    $dir = 'desc';
    $sort = 'date';

    if (isset($_GET['com_dir'])) {
      if ($_GET['com_dir'] == "asc") {
        $dir = 'asc';
      }
    }
    if (isset($_GET['com_sort'])) {
      if ($_GET['com_sort'] == "replies") {
        $sort = 'replies';
      }
    }

    $urlParts = parse_url($content);

    $urlPre = $urlParts['scheme'] ."://". $urlParts['host'] ."/". $urlParts['path'];

    $urlParams = 'com_sort='.$sort.'&com_dir='.$dir;

    if ($urlParts['query']) {
      $urlPost = $urlParts['query'] ."&". $urlParams;
    } else {
      $urlPost = "?". $urlParams;
    }
    
    return $urlPre . $urlPost . ($urlParts['fragment'] ? '#'.$urlParts['fragment'] : '');
  }

  /**
   * 
   *
   * @since     1.0.0
   * @return    array    
   */
  public function comments_ui() {
    // default sort options
    $dateState = 'is-desc';
    $direction = 'is-desc';
    $inputDir = 'desc';
    $inputSort = 'date';
    $repliesState = 'is-inactive';
    $sort = 'date';

    if (isset($_GET['com_dir'])) {
      if ($_GET['com_dir'] == "asc") {
        $direction = 'is-asc';
        $inputDir = 'asc';
      }
    }


    // change defaults
    if (isset($_GET['com_sort'])) {
        if ($_GET['com_sort'] == "replies") {
          $repliesState = $direction;
          $dateState = 'is-inactive';
          $inputSort = 'replies';
        } else {
          $repliesState = 'is-inactive';
          $dateState = $direction;
          $inputSort = 'date';
        }
    }
    ?>
    <form class='js-co-form' method='get' action=''>
      <input class='js-co-input-sort' type='hidden' name='com_sort' value='<?php echo $inputSort; ?>'>
      <input class='js-co-input-dir' type='hidden' name='com_dir' value='<?php echo $inputDir; ?>'>
      <button type='button' class='btn co_btn-sort co_btn-sort--date js-co-btn-sort-date' data-state='<?php echo $dateState; ?>'>Date</button>
      <button type='button' class='btn co_btn-sort co_btn-sort--replies js-co-btn-sort-replies' data-state='<?php echo $repliesState; ?>'>Replies</button>
    </form>
    <?php 
  }





}
