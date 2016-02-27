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
    $post_id = false;
    $search_term = false;
    $start_date = false;
    $end_date = false;
    $selected_range = false;
    $reset_results = false;
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
        $query_array = array($post_id,'%'.$search_term.'%','%'.$search_term.'%');
        $found_comments = $wpdb->get_results( 
            $wpdb->prepare(
                "
                SELECT *
                FROM $wpdb->comments
                WHERE comment_post_id = %d
                AND (comment_content LIKE %s OR comment_author LIKE %s)
                ",
                $query_array
            )
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
        $query_array = array($post_id,$start_date.' 00:00:00',$end_date.' 23:59:59');
        $found_comments = $wpdb->get_results( 
            $wpdb->prepare(
                "
                SELECT *
                FROM $wpdb->comments
                WHERE comment_post_id = %d
                AND comment_date >= %s
                AND comment_date <= %s
                ",
                $query_array
            )
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
        $query_array = array($post_id,$selected_start_range,$selected_end_range);
        $found_comments = $wpdb->get_results( 
            $wpdb->prepare(
                "
                SELECT *
                FROM $wpdb->comments
                WHERE comment_post_id = %d
                AND comment_date >= %s
                AND comment_date <= %s
                ",
                $query_array
            )
        );
        
        return_comment_list($found_comments);
    } else
    
/**
 * Loading requested search results if a combination is sought
 * Match preset range selected by user
 * By default returns newest to oldest
 * Echos HTML
 */    
        
    if($selected_range||$start_date&&$end_date||$search_term) {
        // If has range and start and end, use the range
        if($selected_range!=='default'&&start_date&&end_date) {
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
            $query_array = array($post_id,$selected_start_range,$selected_end_range,'%'.$search_term.'%','%'.$search_term.'%');
            $found_comments = $wpdb->get_results( 
                $wpdb->prepare(
                    "
                    SELECT *
                    FROM $wpdb->comments
                    WHERE comment_post_id = %d
                    AND comment_date >= %s
                    AND comment_date <= %s
                    AND (comment_content LIKE %s OR comment_author LIKE %s)
                    ",
                    $query_array
                )
            );
            return_comment_list($found_comments);
            
        } else {
            
            global $wpdb;
            $query_array = array($post_id,$start_date.' 00:00:00',$end_date.' 23:59:59','%'.$search_term.'%','%'.$search_term.'%');
            $found_comments = $wpdb->get_results( 
                $wpdb->prepare(
                    "
                    SELECT *
                    FROM $wpdb->comments
                    WHERE comment_post_id = %d
                    AND comment_date >= %s
                    AND comment_date <= %s
                    AND (comment_content LIKE %s OR comment_author LIKE %s)
                    ",
                    $query_array
                )
            );
            return_comment_list($found_comments);
            
        }
    }

    if($reset_results) {
        global $wpdb;
        $query_array = array($post_id);
        $found_comments = $wpdb->get_results( 
            $wpdb->prepare(
                "
                SELECT *
                FROM $wpdb->comments
                WHERE comment_post_id = %d
                ",
                $query_array
            )
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