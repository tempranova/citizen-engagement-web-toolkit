<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://oakenfold.ca
 * @since             1.0.0
 * @package           Co_Mment_Sort
 *
 * @wordpress-plugin
 * Plugin Name:       Co mment sort
 * Plugin URI:        http://oakenfold.ca/co-mment-sort-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Chad Oakenfold
 * Author URI:        http://oakenfold.ca/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       co-mment-sort
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-co-mment-sort-activator.php
 */
function activate_co_mment_sort() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-co-mment-sort-activator.php';
	Co_Mment_Sort_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-co-mment-sort-deactivator.php
 */
function deactivate_co_mment_sort() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-co-mment-sort-deactivator.php';
	Co_Mment_Sort_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_co_mment_sort' );
register_deactivation_hook( __FILE__, 'deactivate_co_mment_sort' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-co-mment-sort.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_co_mment_sort() {

	$plugin = new Co_Mment_Sort();
	$plugin->run();

}
run_co_mment_sort();
