<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://tempranova.com
 * @since             0.0.1
 * @package           comment-filtering
 *
 * @wordpress-plugin
 * Plugin Name:       Comment Filtering
 * Plugin URI:        http://tempranova.com/custom-plugin-for-comment-filtering/
 * Description:       Allow users to search comments by terms and filter by time periods. Makes use of Bootstrap Timepicker.
 * Version:           1.0.0
 * Author:            Victor Temprano
 * Author URI:        http://tempranova.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       comment-filtering
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Adds Bootstrap tabs with input boxes and buttons for search and dates, just before the comments_template is output
 * Makes use of https://eternicode.github.io/bootstrap-datepicker
 */
function cf_filtering_add_input_boxes() { 
  // Getting appropriate amount of days ago the post was created
  $post_creation_date = get_the_date('Y-m-d');
  $current_date = current_time('Y-m-d');
  $datetime1 = date_create($post_creation_date);
  $datetime2 = date_create($current_date);
  $interval = date_diff($datetime1, $datetime2);
?>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div>
              <input type="hidden" id="comment_filtering_plugin_dir" value="<?php echo plugin_dir_url(__FILE__); ?>">
              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation"><a class="cf_select_tab" href="#cf_search_by" aria-controls="cf_search_by" role="tab" data-toggle="tab">Search by term or date</a></li>
              </ul>

              <div class="tab-content">
                <div role="tabpanel" class="tab-pane" id="cf_search_by">
                    <div class="form-group">
                        <input type="text" class="form-control" name="comment_filtering_search" placeholder="Search term or author...">
                    </div>
                    <p><strong>Filter by date:</strong></p>
                    <div class="form-group">
                        <div id="comment_filtering_datepicker">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="input-sm form-control" name="start" placeholder="<?php echo $post_creation_date; ?>" />
                                <input type="hidden" name="time_ago" value="<?php echo $interval->days; ?>">
                                <span class="input-group-addon">to</span>
                                <input type="text" class="input-sm form-control" name="end" placeholder="<?php echo $current_date; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        or <select id="cf_selected_range" class="form-control">
                            <option value="default">Select time span</option>
                            <option value="today">Today only</option>
                            <option value="week">Last 7 days</option>
                            <option value="month">Last 30 days</option>
                            <option value="all">All comments</option>
                        </select>
                    </div>
                    <button class="comment_filtering_go btn btn-info">Search comments</button>
                    <button class="comment_filtering_reset btn btn-default">See all comments</button>
                </div>
              </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12"></div>
    </div>
<?php }
add_action('comment_form_after','cf_filtering_add_input_boxes');

/**
 * Enqueue JS for AJAX and CSS for minor styling of buttons
 * JS always loaded after jQuery, in footer, so can make use of jQuery directly
 * Should this only be loaded on appropriate pages?
 */
function cf_filter_comments_custom_scripts() {
    wp_register_script( 'bootstrap-datepicker-min-js', plugin_dir_url(__FILE__) . '/js/bootstrap-datepicker.min.js', array('jquery'));
    wp_register_script( 'filter-comments-custom-js', plugin_dir_url(__FILE__) . '/js/custom.js', array('jquery','bootstrap-datepicker-min-js'));
    wp_enqueue_style( 'bootstrap-datepicker-min-css', plugin_dir_url(__FILE__) . '/css/bootstrap-datepicker.min.css' );
    wp_enqueue_style( 'filter-comments-custom-css', plugin_dir_url(__FILE__) . '/css/custom.css' );
	wp_enqueue_script( 'bootstrap-datepicker-min-js', plugin_dir_url(__FILE__) . '/js/bootstrap-datepicker.min.js', array('jquery'),'0.7.7',true);
	wp_enqueue_script( 'filter-comments-custom-js', plugin_dir_url(__FILE__) . '/js/custom.js', array('jquery','bootstrap-datepicker-min-js'),'0.7.7',true);
}

add_action( 'wp_enqueue_scripts', 'cf_filter_comments_custom_scripts' );

?>