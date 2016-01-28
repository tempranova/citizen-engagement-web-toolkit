<?php
class CommentSorting extends Walker {

    // Tell Walker where to inherit it's parent and id values
    var $db_fields = array(
        'parent' => 'comment_parent', 
        'id'     => 'comment_ID' 
    );

    /**
     * At the start of each element, output a <li> and <a> tag structure.
     * 
     * Note: Menu objects include url and title properties, so we will use those.
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $output .= sprintf( "\n%s %s %s</br>\n",
            $item->comment_author,
            $item->comment_author_email,
            $item->comment_content
        );
    }

}

 $args = array(
   'post_id' => 4 // use post_id, not post_ID
 );
 $comments = get_comments($args);

// 4. Create a new instance of our walker...
$walk = new CommentSorting();

// 5. Walk the tree and render the returned output as a one-dimensional array
print_r( $walk->walk( $comments, -1 ) );
