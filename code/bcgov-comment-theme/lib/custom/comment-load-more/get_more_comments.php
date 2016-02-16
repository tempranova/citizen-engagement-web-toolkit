<?php 
    $post_id = $_GET["post_id"];
    $parent_comment_id = $_GET["parent_id"];
    $comment_index = $_GET["comment_index"];
    require_once( dirname(dirname(dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php' );
    $posts_per_page = get_option('comments_per_page');

    // If this is loading child posts
    if($parent_comment_id) {
        // This gets me all this level of replies; although doesn't tell me about next level of replies
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
            // Do another DB call to find out if they have children too
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

    // If this is expanding an amount of posts
    if(!$parent_comment_id) {
        // This gets me top level comments
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
            // Do another DB call to find out if they have children too
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