<?php
/**
 * Takes care of AJAX request and returns HTML
 *
 *
 * @link       http://tempranova.com
 * @since      1.0.0
 *
 * @package    comment-filtering
 */

/**
 * First, get various variables from the query string (this will determine what function we run)
 * NEED TO DO VALIDATION ON THESE ON BOTH ENDS
 */
    $post_id = $_GET["post_id"];
    $search_term = $_GET["search_term"];
    $start_date = $_GET["start_date"];
    $end_date = $_GET["end_date"];
    $selected_range = $_GET["selected_range"];

/**
 * Load basic WP functions
 * 
 */
    require_once( dirname(dirname(dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php' );

/**
 * Loading requested search results
 * Match parent_id and searches within name and content for term
 * By default returns newest to oldest
 * Echos HTML
 */
    if($search_term) {
        global $wpdb;
        $found_comments = $wpdb->get_results( 
            "
            SELECT *
            FROM $wpdb->comments
            WHERE comment_post_id = " . $post_id . "
            AND (comment_content LIKE '%" . $search_term . "%' OR comment_author LIKE '%" . $search_term . "%')
            "
        );
        
        return_comment_list($found_comments);
    }

/**
 * Loading requested search results
 * Match range specified by user
 * By default returns newest to oldest
 * Echos HTML
 */
    if($start_date&&$end_date) {
        global $wpdb;
        $found_comments = $wpdb->get_results( 
            "
            SELECT *
            FROM $wpdb->comments
            WHERE comment_post_id = " . $post_id . "
            AND comment_date >= '" . $start_date . " 00:00:00'
            AND comment_date <= '" . $end_date . " 23:59:59'
            "
        );
        
        return_comment_list($found_comments);
    }

/**
 * Loading requested search results
 * Match preset range selected by user
 * By default returns newest to oldest
 * Echos HTML
 */
    if($selected_range) {
        $selected_start_range = '';
        $selected_end_range = '';
        switch ($selected_range) {
            case 'today':
                $selected_start_range = current_time('Y-m-d') . ' 00:00:00';
                $selected_end_range = current_time('Y-m-d') . ' 23:59:59';
                break;
            case 'week':
                $selected_start_range = current_time('Y-m-d', strtotime('-7 days')) . ' 00:00:00';
                $selected_end_range = current_time('Y-m-d') . ' 23:59:59';
                break;
            case 'month':
                $selected_start_range = current_time('Y-m-d', strtotime('-30 days')) . ' 00:00:00';
                $selected_end_range = current_time('Y-m-d') . ' 23:59:59';
                break;
        }
        
        global $wpdb;
        $found_comments = $wpdb->get_results( 
            "
            SELECT *
            FROM $wpdb->comments
            WHERE comment_post_id = " . $post_id . "
            AND comment_date >= '" . $selected_start_range . "'
            AND comment_date <= '" . $selected_end_range . "'
            "
        );
        
        return_comment_list($found_comments);
    }

/**
 * Function takes sql result, turns to array and passes to modified wp_list_comments
 */
    function return_comment_list($found_comments) {
        $comments_to_add = [];
        foreach ( $found_comments as $comment ) {
            array_push($comments_to_add, $comment);
        }
        wp_list_comments(['style' => 'ol', 'short_ping' => true, 'callback' => 'bcgov_comments'],$comments_to_add);
    }

?>