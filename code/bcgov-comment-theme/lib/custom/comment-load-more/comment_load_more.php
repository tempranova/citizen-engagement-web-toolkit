<?php
/**
 * @package Load More
 * @version 1.0
 */
/*
Plugin Name: Load More (Comments)
Plugin URI: http://tempranova.com
Description: This plugin replaces default pagination with an AJAXy "Load More" functionality.
Author: Victor Temprano
Version: 1.0
Author URI: http://tempranova.com
*/

// To do
// - Fix up some spacing, plural of replies, wording, etc
// Can probably combine JS functions

// Ideas for more work
// - Could fix number of comments so it reflects all subcomments as well
// - Could add ability for user to set custom text without using function for Next Comments

// Bugs and fixes
// - Not properly marking class with correct depth (always depth-1)
// - Try to avoid doing the really big query when it starts, maybe do an AJAX call from the very start

// First, create a filter to initially not show any comment that is a reply (anything with a comment_parent)
function remove_child_comments( $comments , $post_id ){ 
    $parent_comments_only = [];
    $number_of_children = new stdClass();
    $pagination_number = get_option('comments_per_page');
    // Go over them, reverse so children are always gone over first to count their number correctly
    $comments  = array_reverse($comments);
    foreach($comments as $key=>$comment) {
        $comment_parent = $comment->{'comment_parent'};
        if($comment_parent!=='0') {
            $number_of_children->{$comment->{'comment_parent'}} += 1;
        }
        // Comments with no parent have a value of 0
        if($comment_parent=='0') {
            // This is only DIRECT children
            $comment->{'parent_of'} = $number_of_children->{$comment->{'comment_ID'}};
            array_push($parent_comments_only,$comment);
        }
    }
    $parent_comments_only = array_reverse($parent_comments_only);
//    pr($parent_comments_only);
    return $parent_comments_only;
}
add_filter( 'comments_array' , 'remove_child_comments' , 10, 2 ); 

// Simple way to add plugin directory so that jQuery can get it easily
// This also adds a secret initializer for the load-more-comments in case it doesn't get loaded right away
function add_plugin_dir() {
    echo '<input type="hidden" id="comment-ajax-plugin-directory" value="' . plugin_dir_url(__FILE__) . '">';
    echo '<input type="hidden" class="load-more-comments">';
    echo '<input type="hidden" id="comments-paged" value="' . get_option('comments_per_page') . '">';
}
add_action('comment_form_before','add_plugin_dir');

// Add a "Load More" button under each comment that has subcomments
// This sneakily hooks into the Reply button/link, to avoid mussing with template files
// Uses same style and layout as the old "newer/older comments" button
function add_load_more($reply_link_html, $args, $comment) {
//    pr($comment);
    if($comment->{'parent_of'}>0) {
        $reply_link_html = $reply_link_html . '<ul class="pager reply-top-level" style="text-align:left;"><li class="next load-more-comments"><a style="float:none;">Load ' . $comment->{'parent_of'} . ' More</a></li></ul>';
    }
    return $reply_link_html;
}
add_filter( "comment_reply_link", "add_load_more", 420, 3 );

// Add class to paginated link, and pull to left
// User can change the text to whatever they want using https://codex.wordpress.org/Template_Tags/next_comments_link
function your_function($atts) {
    return 'class="load-more-comments-paginated paged-' . get_option('comments_per_page') . '" style="float:left;"';
}
add_filter( 'next_comments_link_attributes', 'your_function', 10, 1 );


// Enque scripts for the AJAX work
function placespeak_scripts() {
    wp_register_script( 'custom-js', plugin_dir_url(__FILE__) . '/js/custom.js', array('jquery'));
    wp_enqueue_style( 'custom-css', plugin_dir_url(__FILE__) . '/css/custom.css' );
	wp_enqueue_script( 'custom-js', plugin_dir_url(__FILE__) . '/js/custom.js', array('jquery'),'0.7.7',true);
}

add_action( 'wp_enqueue_scripts', 'placespeak_scripts' );

// Pretty print for looking at arrays
function pr($var) { print '<pre>'; print_r($var); print '</pre>'; }

/* 
// User Options screen
add_action( 'admin_menu', 'my_plugin_menu' );
function my_plugin_menu() {
	add_options_page( 'Comment Load More Options', 'Comment Load More', 'manage_options', 'comment-load-more', 'my_plugin_options' );
}

function my_plugin_options() {
    
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    
    $pagination_number = get_option('comment-load-more_pagination');
    ?>
    
	<div class="wrap">
        <h3>Options</h3>
        <p>Set pagination number. This is how many maximum comments will be loaded at once. <strong>This can conflict with the pagination setting on the Discussion page, so please uncheck it there.</strong></p>
        <form action="" method="post">
            <input type="text" name='pagination-number' value="<?php if($pagination_number) { echo $pagination_number; } else { echo '10'; } ?>"> <strong>(Default: 10)</strong>
            <br>
            <input type="submit" name="set-pagination-number" value="Save">
        </form>
	</div>

<?php }

function set_pagination_number() {
	if ( isset( $_POST['set-pagination-number'] ) ) {
        $pagination_number = update_option('comment-load-more_pagination',$_POST['pagination-number']);
    }
};
set_pagination_number();
*/

?>