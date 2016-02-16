<?php 
/**
 * Takes care of AJAX request and returns HTML
 *
 *
 * @link       http://tempranova.com
 * @since      1.0.0
 *
 * @package    comment-load-more
 */

/**
 * First, get various variables from the query string (this will determine what function we run)
 * 
 */
    $post_id = $_GET["post_id"];
    $parent_comment_id = $_GET["parent_id"];
    $comment_index = $_GET["comment_index"];

/**
 * Load basic WP functions
 * 
 */
    require_once( dirname(dirname(dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php' );

/**
 * Get the desired pagination number from wp_options
 * 
 */
    $posts_per_page = get_option('comments_per_page');


/**
 * Loading reply-level posts
 * Match comment_parent_id, and comment_post_ID, with pagination and offset
 * A set of secondary calls are performed to see if any of these comments have children (if they do, it is added as 'parent_of')
 * Echos HTML
 */
    if($parent_comment_id) {
        global $wpdb;
        $new_comments_from_db = $wpdb->get_results( 
            "
            SELECT *
            FROM $wpdb->comments
            WHERE comment_parent = " . $parent_comment_id . "
                AND comment_post_ID = " . $post_id . "
            LIMIT $comment_index, $posts_per_page
            "
        );

        $comments_to_add = [];
        foreach ( $new_comments_from_db as $comment ) {
            $comment_parent = $comment->{'comment_parent'};
            $count_children_of_children = $wpdb->get_var( 
                "
                SELECT COUNT(*)
                FROM $wpdb->comments
                WHERE comment_parent = " . $comment->{'comment_ID'} . "
                    AND comment_post_ID = " . $post_id . "
                "
            );
            if($count_children_of_children) {
                $comment->{'parent_of'} = $count_children_of_children;
            }
            array_push($comments_to_add,$comment);
        }
    
        wp_list_comments(['style' => 'ol', 'short_ping' => true, 'callback' => 'bcgov_comments'],$comments_to_add);
    }

/**
 * Loading parent-level posts
 * Match comment_parent to 0, comment_post_ID, with pagination and offset
 * A set of secondary calls are performed to see if any of these comments have children (if they do, it is added as 'parent_of')
 * Echos HTML
 */
    if(!$parent_comment_id) {
        global $wpdb;
        $new_comments_from_db = $wpdb->get_results( 
            "
            SELECT *
            FROM $wpdb->comments
            WHERE comment_parent = 0 AND comment_post_ID = " . $post_id . "
            LIMIT $comment_index, $posts_per_page
            "
        );
        $comments_to_add = [];
        foreach ( $new_comments_from_db as $comment ) {
            $comment_parent = $comment->{'comment_parent'};
            $count_children_of_children = $wpdb->get_var( 
                "
                SELECT COUNT(*)
                FROM $wpdb->comments
                WHERE comment_parent = " . $comment->{'comment_ID'} . " AND comment_post_ID = " . $post_id . "
                "
            );
            if($count_children_of_children) {
                $comment->{'parent_of'} = $count_children_of_children;
            }
            array_push($comments_to_add,$comment);
        }

        wp_list_comments(['style' => 'ol', 'short_ping' => true, 'callback' => 'bcgov_comments'],$comments_to_add);
        
    }

?>