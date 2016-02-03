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

		wp_enqueue_script( $this->co_mment_sort, plugin_dir_url( __FILE__ ) . 'js/co-mment-sort.js', array( 'jquery' ), $this->version, false );

	}

}
