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
 */
    $post_id = filter_var($_GET["post_id"],FILTER_SANITIZE_STRING);
    $search_term = filter_var($_GET["search_term"],FILTER_SANITIZE_STRING);
    $start_date = filter_var($_GET["start_date"],FILTER_SANITIZE_STRING);
    $end_date = filter_var($_GET["end_date"],FILTER_SANITIZE_STRING);
    $selected_range = filter_var($_GET["selected_range"],FILTER_SANITIZE_STRING);
    $reset_results = filter_var($_GET["reset"],FILTER_SANITIZE_STRING);

/**
 * Load basic WP functions
 * 
 */
    require_once( dirname(dirname(dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php' );

/**
 * Loading requested search results if only search term sought
 * Match parent_id and searches within name and content for term
 * By default returns newest to oldest
 * Echos HTML
 */
    if($search_term&&!$start_date&&!$end_date&&$selected_range=='default') {
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
    } else

/**
 * Loading requested search results by range if only range sought
 * Match range specified by user
 * By default returns newest to oldest
 * Echos HTML
 */
    if($start_date&&$end_date&&!$search_term&&$selected_range=='default') {
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
    } else

/**
 * Loading requested search results by preset if only preset sought
 * Match preset range selected by user
 * By default returns newest to oldest
 * Echos HTML
 */
    if($selected_range&&!start_date&&!end_date&&!search_term) {
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
            case 'all':
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
    } else
    
/**
 * Loading requested search results if a combination is sought
 * Match preset range selected by user
 * By default returns newest to oldest
 * Echos HTML
 */    
        
    if($selected_range!=='default'||$start_date&&$end_date||$search_term) {
        // If has range and start and end, use the range
        if($selected_range&&start_date&&end_date) {
            $selected_start_range = '';
            $selected_end_range = '';
            switch ($selected_range) {
                case 'today':
                    $selected_start_range = current_time('Y-m-d') . ' 00:00:00';
                    $selected_end_range = current_time('Y-m-d') . ' 23:59:59';
                    break;
                case 'week':
                    $selected_start_range = date('Y-m-d', strtotime('-7 days')) . ' 00:00:00';
                    $selected_end_range = current_time('Y-m-d') . ' 23:59:59';
                    break;
                case 'month':
                    $selected_start_range = date('Y-m-d', strtotime('-30 days')) . ' 00:00:00';
                    $selected_end_range = current_time('Y-m-d') . ' 23:59:59';
                    break;
                case 'all':
                    $selected_start_range = '2000-01-01 00:00:00';
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
                AND (comment_content LIKE '%" . $search_term . "%' OR comment_author LIKE '%" . $search_term . "%')
                "
            );
            return_comment_list($found_comments);
            
        } else {
            
            global $wpdb;
            $found_comments = $wpdb->get_results( 
                "
                SELECT *
                FROM $wpdb->comments
                WHERE comment_post_id = " . $post_id . "
                AND comment_date >= '" . $start_date . " 00:00:00'
                AND comment_date <= '" . $end_date . " 23:59:59'
                AND (comment_content LIKE '%" . $search_term . "%' OR comment_author LIKE '%" . $search_term . "%')
                "
            );
            return_comment_list($found_comments);
            
        }
    }

    if($reset_results) {
        global $wpdb;
        $found_comments = $wpdb->get_results( 
            "
            SELECT *
            FROM $wpdb->comments
            WHERE comment_post_id = " . $post_id . "
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