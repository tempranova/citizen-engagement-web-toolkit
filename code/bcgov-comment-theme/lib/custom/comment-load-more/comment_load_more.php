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
 * @package           comment-load-more
 *
 * @wordpress-plugin
 * Plugin Name:       Comment Load More
 * Plugin URI:        http://tempranova.com/custom-plugin-for-ajaxy-wordpress-comments/
 * Description:       Allow AJAXy loading of comments.
 * Version:           1.0.0
 * Author:            Victor Temprano
 * Author URI:        http://tempranova.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       comment-load-more
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * First, create a filter to initially not show any comment that is a reply (anything with a comment_parent)
 * This reverses the array and checks for a parent variable, ensuring that number of child comments is passed as array to be checked later
 */
function clm_remove_child_comments( $comments , $post_id ){ 
    $parent_comments_only = [];
    $number_of_children = new stdClass();
    $comments  = array_reverse($comments);
    foreach($comments as $key=>$comment) {
        $comment_parent = $comment->{'comment_parent'};
        if (isset($number_of_children->{$comment->{'comment_parent'}})) {
            if($comment_parent!=='0') {
                $number_of_children->{$comment->{'comment_parent'}}++;
            }
            if($comment_parent=='0') {
                if (isset($number_of_children->{$comment->{'comment_ID'}})) {
                    $comment->{'parent_of'} = $number_of_children->{$comment->{'comment_ID'}};
                } else {
                    $comment->{'parent_of'} = 0;
                }
                array_push($parent_comments_only,$comment);
            }
        } else {
            $number_of_children->{$comment->{'comment_parent'}} = 0;
        }
    }
    $parent_comments_only = array_reverse($parent_comments_only);
    return $parent_comments_only;
}
add_filter( 'comments_array' , 'clm_remove_child_comments' , 10, 2 ); 

/**
 * Some information that JS needs to do AJAX properly
 * This also adds a secret initializer for the load-more-comments click event listener, in case others aren't loaded right away
 */
function clm_add_plugin_dir() {
    echo '<input type="hidden" id="comment-ajax-plugin-directory" value="' . plugin_dir_url(__FILE__) . '">';
    echo '<input type="hidden" class="load-more-comments">';
    echo '<input type="hidden" id="comments-paged" value="' . get_option('clm_comments_per_page') . '">';
}
add_action('comment_form_before','clm_add_plugin_dir');

/**
 * Add a "Load More" button under each comment that has replies
 * This sneakily hooks into the Reply button/link, to avoid mussing with template files
 * Uses same style and layout as the old "newer/older comments" button
 */
function clm_add_load_more($reply_link_html, $args, $comment) {
    if($comment->{'parent_of'}>0) {
        $reply_link_html = $reply_link_html . '<ul class="pager reply-top-level" style="text-align:left;"><li class="next load-more-comments"><a style="float:none;">Load ' . $comment->{'parent_of'} . ' More</a></li></ul>';
    }
    return $reply_link_html;
}
add_filter( "comment_reply_link", "clm_add_load_more", 420, 3 );

/**
 * Add class to paginated link, and pull to left
 * User can change the text to whatever they want using https://codex.wordpress.org/Template_Tags/next_comments_link
 * Number of "paged" can be set in Settings > Discussion
 */
function clm_more_comments_atts($atts) {
    return 'class="load-more-comments-paginated paged-' . get_option('clm_comments_per_page') . '" style="float:left;"';
}
add_filter( 'next_comments_link_attributes', 'clm_more_comments_atts', 10, 1 );


/**
 * Enqueue JS for AJAX and CSS for minor styling of buttons
 * JS always loaded after jQuery, in footer, so can make use of jQuery directly
 */
function clm_load_comments_custom_scripts() {
    wp_register_script( 'clm-custom-js', plugin_dir_url(__FILE__) . '/js/custom.js', array('jquery'));
    wp_enqueue_style( 'custom-css', plugin_dir_url(__FILE__) . '/css/custom.css' );
	wp_enqueue_script( 'clm-custom-js', plugin_dir_url(__FILE__) . '/js/custom.js', array('jquery'),'0.7.7',true);
}

add_action( 'wp_enqueue_scripts', 'clm_load_comments_custom_scripts' );


/**
 * Adding PlaceSpeak item to Settings in wp-admin
 * 
 */
add_action( 'admin_menu', 'clm_plugin_menu' );
function clm_plugin_menu() {
	add_options_page( 'Comment Load More Options', 'Comment Load More', 'manage_options', 'comment-load-more', 'clm_plugin_options' );
}
/**
 * PlaceSpeak Options page
 * Has table storage options, ability to add a new app, and listing of apps with ability to edit them and archive
 */
function clm_plugin_options() {
    
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    
    $posts_per_page = get_option('clm_comments_per_page');
    
    ?>
    
	<div class="wrap">
        <h3>Comment Load More Options</h3>
        <form action="" method="POST">
            <input type="number" name="number_of_clm_comments" placeholder="10" value="<?php echo $posts_per_page; ?>"></input>The number of comments loaded each time the user clicks on "Load More"</strong></p>
            <input type="submit" name="clm_comments_per_page" value="Save">
        </form>
    </div>
<?php }

function clm_save_comments_per_page() {
	if ( isset( $_POST['clm_comments_per_page'] ) ) {
        update_option('clm_comments_per_page', $_POST['number_of_clm_comments']);
        update_option('comments_per_page', $_POST['number_of_clm_comments']);
        update_option('page_comments', 1);
    }
}
clm_save_comments_per_page();

?>