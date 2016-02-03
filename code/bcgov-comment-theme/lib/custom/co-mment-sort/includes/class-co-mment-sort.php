<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://oakenfold.ca
 * @since      1.0.0
 *
 * @package    Co_Mment_Sort
 * @subpackage Co_Mment_Sort/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Co_Mment_Sort
 * @subpackage Co_Mment_Sort/includes
 * @author     Your Name <email@example.com>
 */
class Co_Mment_Sort {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Co_Mment_Sort_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $co_mment_sort    The string used to uniquely identify this plugin.
	 */
	protected $co_mment_sort;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->co_mment_sort = 'co-mment-sort';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Co_Mment_Sort_Loader. Orchestrates the hooks of the plugin.
	 * - Co_Mment_Sort_i18n. Defines internationalization functionality.
	 * - Co_Mment_Sort_Admin. Defines all hooks for the admin area.
	 * - Co_Mment_Sort_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-co-mment-sort-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-co-mment-sort-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-co-mment-sort-admin.php';

    /**
     * The class responsible for defining all actions that occur in the public-facing
     * side of the site.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-co-mment-sort-public.php';

    /**
     * stable sort library
     * https://github.com/vanderlee/PHP-stable-sort-functions
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/PHP-stable-sort-functions/classes/StableSort.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/PHP-stable-sort-functions/functions/sarsort.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/PHP-stable-sort-functions/functions/sasort.php';

    /**
     * Custom walker returns arrays from comments
     * 
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-co-mment-sort-walker.php';

		$this->loader = new Co_Mment_Sort_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Co_Mment_Sort_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Co_Mment_Sort_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Co_Mment_Sort_Admin( $this->get_co_mment_sort(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Co_Mment_Sort_Public( $this->get_co_mment_sort(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

    // $this->loader->add_action( 'the_content', $plugin_public, 'modify_post_content' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_co_mment_sort() {
		return $this->co_mment_sort;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Co_Mment_Sort_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public function get_version() {
    return $this->version;
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

  /**
   * 
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public function get_comment_sort_date($direction_bool) {
    // gets comments
    $args_get = array(
      'post_id' => get_the_ID(),
    );

    $comments = get_comments( $args_get );

    // returns array[0]= reply count, array[1]=date stamps
    $walk = new Walker_Co_Mment_Sort();
    $walkOutput = $walk->walk( $comments, 0 );
    $walkOutputDate = $walkOutput[1];

    if ($direction_bool = true) {
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
   * @return    string    The version number of the plugin.
   */
  public function get_comment_sort_replies($direction_bool) {
    // gets comments
    $args_get = array(
      'post_id' => get_the_ID(),
    );

    $comments = get_comments( $args_get );

    // returns array[0]= reply count, array[1]=date stamps
    $walk = new Walker_Co_Mment_Sort();
    $walkOutput = $walk->walk( $comments, 0 );
    $walkOutputReplies = $walkOutput[0];

    if ($direction_bool = true) {
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
   * @return    string    The version number of the plugin.
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

}
